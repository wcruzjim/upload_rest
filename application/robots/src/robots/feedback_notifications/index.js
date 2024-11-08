const CONFIG = require('../../config/config');
const BOT_CONFIG = require('./config/config');
const dynamic_request_model = require("./models/dynamic_request_model");
const general_helper = require('../../helpers/general');
const helper_logs = require("../../helpers/logs");
const { get_access_token } = require('../../models/api_auth');
const io = require('socket.io-client');
const redis = require('redis').createClient(CONFIG.integrations.redis);
const socket = io(CONFIG.integrations.automatic_notifications.sockets_host + ':' + CONFIG.integrations.automatic_notifications.sockets_port , { upgrade : false, transports : ['websocket'], rejectUnauthorized : false, query : {source : 'cosmos_worker', name : 'events_notifications'}});
let api_token = '';

redis.on('error', function(){
  general_helper.showDetails('Worker Feedback Notifications : Redis error');
});
socket.on('connect', function(){
  general_helper.showDetails('Worker Feedback Notifications : Socket connected');
});
socket.on('error', function(err){
  general_helper.showDetails(`Worker Feedback Notifications : Socket error ${err}`);
});
socket.on('event', function(){
  general_helper.showDetails('Worker Feedback Notifications : Socket event');
});
socket.on('disconnect', function(err){
  general_helper.showDetails(`Worker Feedback Notifications : Socket disconnected ${err}`);
});

// Subscribe to channel worker Feedback  notifications
redis.subscribe('worker:feedback_notifications');


// Listen incoming messages
redis.on('message', function(channel, message){

  switch(channel){
    case 'worker:feedback_notifications' : {
      switch(message){
        case 'restart' : {
          general_helper.showDetails('worker : Restart');
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

async function search_next_feedbacks(){
    try{

        // Calculate next timestamp to search events
        let next_date = new Date();
        let txt_year = next_date.getFullYear();
        let txt_month = next_date.getMonth() + 1;
        txt_month = txt_month < 10 ? '0' + txt_month : txt_month;
        let txt_day = parseInt(next_date.getDate()) < 10 ? ('0' + next_date.getDate() ) : next_date.getDate();
        let txt_hours = parseInt(next_date.getHours()) < 10 ? ('0' + next_date.getHours() ) : next_date.getHours();
        let txt_minutes = parseInt(next_date.getMinutes()) < 10 ? ('0' + next_date.getMinutes() ) : next_date.getMinutes();
        let txt_seconds = '00';
        
        let parsed_date = `${txt_year}-${txt_month}-${txt_day} ${txt_hours}:${txt_minutes}:${txt_seconds}`;
  
        let feedbacks = await dynamic_request_model.get_pending_feedbacks(parsed_date, api_token);
    
        general_helper.showDetails(`Looking for feedbacks of: ${parsed_date}`);
        general_helper.showDetails(`Worker feedbacks: Notifications sent (${feedbacks.length})`);

        feedbacks.forEach(function(event){
            
            let notify_to_send = {...BOT_CONFIG.default_notify_config};
            notify_to_send.target = event.target_usernames;
            notify_to_send.notification_config.title = 'Solicitud de feedback';
            notify_to_send.notification_config.text = 'Tienes una nueva solicitud de feedback en espera de tu calificación !.';
            notify_to_send.notification_config.text += '<img src="data:image/png;base64,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">';

            socket.emit('message_user_by', notify_to_send);

            socket.emit('message', {
                "pattern" : "username",
                "target" :  event.target_usernames,
                "packet_type" : "h",
                "skip_log" : true
            });

          helper_logs.create_log(8, notify_to_send, 'debug', 'worker_feedback');
        });
    }
    catch(err){
      general_helper.showDetails(`Error search next feedback ${err}`);
    }
}


function refresh_token(){
  setTimeout(async function(){
    api_token = await get_access_token();
    refresh_token()
  },CONFIG.integrations.api.token_refresh_time)  
}

async function run(){
  api_token = await get_access_token();
  setInterval(search_next_feedbacks, BOT_CONFIG.events.interval_get_events);
  refresh_token();
}

run();


