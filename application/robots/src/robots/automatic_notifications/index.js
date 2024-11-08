const CONFIG = require('../../config/config');
const general_helper = require('../../helpers/general');
const helper_logs = require("../../helpers/logs");
const helper_notification_logs = require("../../helpers/notification_logs");
const notification_log_model = require("../../models/notification_logs");
const notification_model = require("./models/notification_model");
const { get_access_token } = require('../../models/api_auth');
const io = require('socket.io-client');
const redis = require('redis').createClient(CONFIG.integrations.redis);
const socket = io(CONFIG.integrations.automatic_notifications.sockets_host + ':' + CONFIG.integrations.automatic_notifications.sockets_port , { upgrade : false, transports : ['websocket'], rejectUnauthorized : false, query : {source : 'cosmos_worker', name : 'events_notifications'}});

let api_token = '';

// Bot is running or is stopped
let active_bot = true;

// // list notifications
let list_notifications = [];

// list agent's connections
let list_connections = [];

let recent_users = [];
let documents = {};

let documents_platform = {};

// list of sent notifications to the users since server is running
let recent_notifications = [];

let packet_prototype = {
  target : "610926",
  packet_type : "a",
  pattern : "id_avaya",
  notification_config : {
    type : 0,
    title : "",
    text : "",
    width : 400,
    height: 170,
    center : true,
    context : "warning",
    movable: true,
    icon : 'fa-bell'
  }
};

redis.on('error', function(){
  general_helper.showDetails('Worker Automatic Notifications : Redis error');
});
socket.on('connect', function(){
  general_helper.showDetails('Worker Automatic Notifications : Socket connected');
});
socket.on('error', function(err){
  general_helper.showDetails(`Worker Automatic Notifications : Socket error ${err}`);
});
socket.on('event', function(){
  general_helper.showDetails('Worker Automatic Notifications : Socket event');
});
socket.on('disconnect', function(err){
  general_helper.showDetails(`Worker Automatic Notifications : Socket disconnected ${err}`);
});


// Subscribe to channel worker automatic notifications
redis.subscribe('worker:automatic_notifications');

// Listen incoming messages
redis.on('message', function(channel, message){
  switch(channel){
    case 'worker:automatic_notifications' : {
      switch(message){
        case 'restart' : {
          general_helper.showDetails('worker : load automatic notifications config');
          get_automatic_notifications();
          break;
        }
        default:
          break;
      }
    }
    default:
      break;
  }
});

//  clear old notification logs
function clear_automatic_notification_logs(){
  if(!CONFIG.integrations.automatic_notifications.clear_notification_logs){
    return;
  }
  notification_model.clear_automatic_notification_logs(CONFIG.integrations.automatic_notifications.hours_storage_notification_logs, CONFIG.integrations.automatic_notifications.automatic_notifications_type_log, api_token);
}



// get list of notifications from db
function get_automatic_notifications(callback){
  if(Object.keys(documents_platform).length < 1){
    setTimeout(function(){
      get_automatic_notifications(callback);
    }, 3000);
    return;
  }

  general_helper.showDetails("Getting list automatic notifications settings");

  notification_model.get_automatic_notifications(api_token)
  .then(function(result){
    list_notifications = result;
    general_helper.showDetails("Loaded configurations : " + list_notifications.length);

    if(callback){
      callback();
    }

    setTimeout(function(){
      get_automatic_notifications();
    }, 60000);
  },
  function(err){
    general_helper.showDetails("Finishing app by db error connection");
    general_helper.handleUncaughtExceptions(err);
  });
}



// get agent's connections from database
function get_phone_connections(){
    notification_model.get_phone_connections(api_token)
    .then(match_connections)
    .catch(function(err){
      setTimeout(get_phone_connections, 30000);
      general_helper.showDetails(err);
    });

}


// // match user's connections with notification list
function match_connections(local_connections){
  list_connections = local_connections;
  list_connections.forEach(function(conn){
    if((conn.reason === null && conn.state === null) || conn.skill === null){
      return;
    }

    conn.reason = conn.reason == null ? '' : conn.reason.toLowerCase();
    conn.state = conn.state == null ? '' : conn.state.toLowerCase();

    // create sha1 by finger_print and id login
    let sha1 = `${conn.finger_print.split("_")[0]}_${conn.id_login}`;

    // check if any configuration match with agent's connection
    let notify_match = undefined;

    try{
        notify_match = list_notifications.find(function(current_notification){

        if(current_notification.pcrc_internal_code == undefined || current_notification.pcrc_internal_code == null){
          return false;
        }

        return (current_notification.state.toLowerCase() == conn.state.toLowerCase() ||
        current_notification.state.toLowerCase() == conn.reason.toLowerCase()) &&
        (current_notification.pcrc_internal_code.toLowerCase() == conn.skill.toLowerCase()) &&
        (parseInt(conn.time) > parseInt(current_notification.time));
      });
    }
    catch(err){
      general_helper.showDetails(err);
      return;
    }


    // Does not find any match
    if(notify_match === undefined){
      return;
    }

    // Check if a notification for the same reason was send to the user before interval time
    if(is_notification_before_time(sha1, notify_match) === true){
      return;
    }


    let document_notify = documents_platform[conn.id_login];

    if(!document_notify){
      document_notify = documents_platform[conn.first_name];
    }

    // send notification to the user
    send_notification(sha1, conn, notify_match, document_notify);
  });



  // start to get agent's connections again
  setTimeout(function(){
    get_phone_connections();
  }, CONFIG.integrations.automatic_notifications.interval_get_connections);

}

// send notification to user
function send_notification(sha1, conn, notify_config, document_notify){

  if(!CONFIG.integrations.automatic_notifications.active){
    return;
  }
  
  if(socket.disconnected){
    return;
  }
  // create a duplicate. To modify the message selected
  var copy_notify = Object.assign({}, notify_config);

  // get a list of messages
  copy_notify.message = copy_notify.message.split("||");

  // if pattern message is azar choose a number
  if(parseInt(notify_config.pattern_message) == 2){

    let azar = Math.round( (general_helper.randomSecure() * (copy_notify.message.length -1 )) );
    copy_notify.message = copy_notify.message[azar];
  }
  else{

    let next_to_send = recent_users[sha1][notify_config.automatic_notifications_id]["sendings"];

    // if next message does not exist. send the last message
    if(copy_notify.message.length < next_to_send ){
      copy_notify.message = copy_notify.message.slice(-1)[0];
    }
    else{
      // send the next message in queue
      copy_notify.message = copy_notify.message[next_to_send - 1];
    }

  } 

  // save into logs
  log_notification(conn, copy_notify);

  log_notification_detail(conn, copy_notify);
  // send real notification
  
  // create copy of packet to send
  let copy_packet_prototype = Object.assign({}, packet_prototype);
  copy_packet_prototype.pattern = "identify";
  copy_packet_prototype.target = document_notify;
  copy_packet_prototype.notification_config.title = copy_notify.title;
  copy_packet_prototype.notification_config.text = copy_notify.message;
  copy_packet_prototype.notification_config.context = get_context_by_type(copy_notify.type);
  copy_packet_prototype.notification_config.position = parseInt(copy_notify.position);

  socket.emit('message_user_by', { skip_log : true, pattern : copy_packet_prototype.pattern, target : copy_packet_prototype.target, packet_type : 'a', notification_config : packet_prototype.notification_config });
}

// //check if is necesary to send notification or wait
function is_notification_before_time(sha1, notify_config){

  let current_time = Date.now();

  // if user is not in the list, add it
  if(recent_users.hasOwnProperty(sha1) == false){
    recent_users[sha1] = {};
  }


  // Check minimum time of send notification ignoring db settings
  if(recent_users[sha1].hasOwnProperty('last_time') == false){
    recent_users[sha1]['last_time'] = current_time;
  }
  else{

    // if time is less than allowed, stop
    let difference_time = ((current_time - recent_users[sha1]['last_time']));
    if(difference_time < CONFIG.integrations.automatic_notifications.interval_send_notification){
      return true;
    }
  }


  // if does not exist any notification attach one in last notify
  if(recent_users[sha1].hasOwnProperty("last_notify_id") == false){
    recent_users[sha1]["last_notify_id"] = notify_config.automatic_notifications_id;
  }

  // if notification was never send, create it the first time
  if(recent_users[sha1].hasOwnProperty(notify_config.automatic_notifications_id) == false){

    recent_users[sha1][notify_config.automatic_notifications_id] = {
      last_time : Date.now(),
      sendings : 1
    };
  }
  else{

    // if time is less than allowed, stop
    let difference_time = ((current_time - recent_users[sha1][notify_config.automatic_notifications_id]["last_time"]) / 1000);
    if( difference_time < notify_config.interval_time ){
      return true;
    }

    // save new time and increase sendings
    recent_users[sha1][notify_config.automatic_notifications_id]["last_time"] = current_time;
    recent_users[sha1][notify_config.automatic_notifications_id]["sendings"] = (recent_users[sha1][notify_config.automatic_notifications_id]["sendings"] + 1);

  }


  // if notify to send is different than before. When is the first notification the code does not enter here
  if(recent_users[sha1]["last_notify_id"] != notify_config.automatic_notifications_id){
    // save new id
    recent_users[sha1]["last_notify_id"] = notify_config.automatic_notifications_id;
    // clear sendings quantity
    recent_users[sha1][notify_config.automatic_notifications_id]["sendings"] = 1;
    recent_users[sha1][notify_config.automatic_notifications_id]["last_time"] = Date.now();
  }

  recent_users[sha1]['last_time'] = current_time;

  return false;
}                         

//add the notification send to the user to the temp queue, then it will be save into database
function log_notification(conn, notify_config){

  if(!CONFIG.integrations.automatic_notifications.save_logs){
    return;
  }

  let current_date = new Date();

  var temp_log = {
    date_send : `${current_date.toLocaleDateString()} : ${current_date.toLocaleTimeString()}`,
    id_login : conn.id_login,
    prefix_login : get_prefix_login(conn.platform),
    skill_internal_code : conn.skill,
    agent_name : conn.first_name,
    agent_time : conn.time,
    notify_title : notify_config.title,
    notify_message : notify_config.message,
    notify_type : notify_config.type,
    notify_state : notify_config.state
  };

  helper_logs.create_log(4, temp_log , 'debug', 'worker_states');
}

//add the notification send to the user to the temp queue, then it will be save into database
function log_notification_detail(conn, notify_config){
  if(!CONFIG.integrations.automatic_notifications.save_logs){
    return;
  }
  if(Object.keys(documents).length > 0){
    
    let current_date = new Date();
    let notifications_json = {
      date_send : `${current_date.toLocaleDateString()} : ${current_date.toLocaleTimeString()}`,
      id_login : conn.id_login,
      prefix_login : get_prefix_login(conn.platform),
      skill_internal_code : conn.skill,
      agent_name : conn.first_name,
      agent_time : conn.time,
      notify_title : notify_config.title,
      notify_message : notify_config.message,
      notify_type : notify_config.type,
      notify_state : notify_config.state
    };
    
    let temp_log = {
      id_login : conn.id_login,
      document:getDocumentLogNotification(documents, conn),
      date : current_date.toISOString().slice(0, 19).replace('T', ' '),
      notifications_json: JSON.stringify(notifications_json),
      skill_internal_code : conn.skill,
      notify_state : notify_config.state,
      agent_time : conn.time,
      notify_title : notify_config.title,
      notify_message : notify_config.message,
      notify_type : notify_config.type,
      automatic_notifications_id:notify_config.automatic_notifications_id
    };
    if(temp_log.document !=''){
      helper_notification_logs.create_log(temp_log);
    }
  }
  
 
}

function getDocumentLogNotification(documents, conn) {
  let platform =  getPlatform(conn.platform);
  if(typeof documents[platform + conn.id_login] !='undefined'){
      return documents[platform + conn.id_login];
  }
  else{
    if(typeof documents[platform + conn.first_name] !='undefined'){
      return documents[platform + conn.first_name];
    }
    else{
      return '';
    }
  }
}

function getPlatform(platform) {
  switch (platform) {
    case 'avaya':
      return 'AVAYA';
    case 'genesys':
      return 'GENMED';
    default:
      return;
  }
}

// get prefix sip plaform by finger print (avaya_dummy_country, genesys_dummy_country)
function get_prefix_login(platform){
  if(platform == 'avaya'){
    return 'id_avaya';
  }
  else{
    return 'username';
  }
}

function get_context_by_type(local_type){

  switch(local_type){
    case "1" : {
      return "info";
    }
    case "2" : {
      return "warning";
    }
    case "3" : {
      return "success";
    }
    case "4" : {
      return "danger";
    }
    default : {
      return "info";
    }
  }
}

// Get current status bot
function get_active_bot(){
  return CONFIG.integrations.automatic_notifications.active;
}

// set status bot
function set_active_bot(new_val){
  CONFIG.integrations.automatic_notifications.active = new_val;
}

function get_documents(){
  return notification_log_model.get_documents(api_token).then(userData => {
    setTimeout(get_documents, 60000);
    if (typeof userData !='undefined') {
      if (userData.length > 0) {
        userData.forEach(user => {
          documents[user.platform + user.id_login]  = user.document;
        });
        return documents;
      }
    }
  }).catch(err => {
    general_helper.showDetails(err);
    setTimeout(get_documents, 60000);
  })
}

function getPhonePlatformUsers(){
  notification_model.getPhonePlatformUsers(api_token)
  .then(users => {
    documents_platform = users;
    setTimeout(getPhonePlatformUsers, CONFIG.integrations.automatic_notifications.interval_get_users_unique);
  })
  .catch(function(err){
    general_helper.showDetails(err);
    setTimeout(getPhonePlatformUsers, CONFIG.integrations.automatic_notifications.interval_get_users_unique);
  });
}

function refresh_token(){
  setTimeout(async function(){
    api_token = await get_access_token();
    refresh_token()
  },CONFIG.integrations.api.token_refresh_time)  
}

// /**
//  * Función de inicio
//  */
async function init(){
  api_token = await get_access_token();
  
  // clear old automatic notification logs
  setInterval(clear_automatic_notification_logs, CONFIG.integrations.automatic_notifications.interval_clear_notification_logs);

  //espera que carguen los documentos
  documents = await get_documents();

  getPhonePlatformUsers();

  get_automatic_notifications(get_phone_connections);
  refresh_token();
}

init();

module.exports = {
  get_automatic_notifications,
  set_active_bot,
  get_active_bot, 
  get_documents
}
