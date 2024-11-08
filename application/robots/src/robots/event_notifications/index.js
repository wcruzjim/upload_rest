const CONFIG = require('../../config/config');
const BOT_CONFIG = require('./config/config');
const schedule_model = require("./models/schedule_model");
const general_helper = require('../../helpers/general');
const helper_logs = require("../../helpers/logs.js");
const { get_access_token } = require('../../models/api_auth');
const io = require('socket.io-client');
const redis = require('redis').createClient(CONFIG.integrations.redis);
const socket = io(CONFIG.integrations.automatic_notifications.sockets_host + ':' + CONFIG.integrations.automatic_notifications.sockets_port , { upgrade : false, transports : ['websocket'], rejectUnauthorized : false, query : {source : 'cosmos_worker', name : 'events_notifications'}});
var api_token = '';

redis.on('error', function(){
  general_helper.showDetails('Worker Events Notifications : Redis error');
});
socket.on('connect', function(){
  general_helper.showDetails('Worker Events  Notifications : Socket connected');
});
socket.on('error', function(err){
  general_helper.showDetails(`Worker Events  Notifications : Socket error ${err}`);
});
socket.on('event', function(){
  general_helper.showDetails('Worker Events  Notifications : Socket event');
});
socket.on('disconnect', function(err){
  general_helper.showDetails(`Worker Events  Notifications : Socket disconnected ${err}`);
});

// Subscribe to channel worker Events  notifications
redis.subscribe('worker:events_notifications');


// Listen incoming messages
redis.on('message', function(channel, message){

  switch(channel){
    case 'worker:events_notifications' : {
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




async function search_next_events(){
    try{

        // Calculate next timestamp to search events
        let next_date = Math.round(Date.now() / 1000) + ( BOT_CONFIG.events.time_before_notification * 60 );

        let events = await schedule_model.get_next_events(next_date, BOT_CONFIG.events.events_to_notify, api_token);

        general_helper.showDetails(`Worker events : Notifications sent (${events.length})`);

        events.forEach(function(event){
            
            let notify_to_send = {...BOT_CONFIG.default_notify_config};
            notify_to_send.target = event.identify;
            notify_to_send.notification_config.title = event.first_name;
            notify_to_send.notification_config.text = `Recuerda que tienes ${event.event_name} a las ${event.event_start}`;
            notify_to_send.notification_config.text += `<img src="data:image/png;base64,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" style="display: block"> Es importante lavar tus manos !`;

            socket.emit('message_user_by', notify_to_send);
            helper_logs.create_log(7, notify_to_send,'debug','worker_events');
        });
    }
    catch(err){
        general_helper.showDetails(`Error search next events ${err}`);
    }
}



async function notify_warn_disconnect_before_leave_schedule(){
    try{

        // Calculate next timestamp to search events
        let next_date = Math.round(Date.now() / 1000) + ( BOT_CONFIG.events.time_before_notification * 60 );
          
        let events = await schedule_model.get_next_pending_finish_schedules(next_date, api_token);
        
        general_helper.showDetails(`Worker events : Notifications sent (${events.length})`);

        events.forEach(function(event){
            
            let notify_to_send_schedule = {...BOT_CONFIG.default_notify_config};
            notify_to_send_schedule.target = event.identify;
            notify_to_send_schedule.notification_config.title = event.first_name;
            notify_to_send_schedule.notification_config.context = 'primary';
            notify_to_send_schedule.notification_config.icon = 'fa-recycle';
            notify_to_send_schedule.notification_config.height = 270;
            notify_to_send_schedule.notification_config.width = 300;
            notify_to_send_schedule.notification_config.dark_mode = false;
            notify_to_send_schedule.notification_config.text = 'Recuerda reiniciar la máquina al finalizar tu turno de trabajo';
            notify_to_send_schedule.notification_config.text += `<span style="text-align:center; display: block; margin-top: 5px; width: 100%; clear: both"><img src="data:image/png;base64,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" > </span>`;
  
            socket.emit('message_user_by', notify_to_send_schedule);
            helper_logs.create_log(13, notify_to_send_schedule, 'debug','worker_events');
        });
    }
    catch(err){
        general_helper.showDetails(`Error search next events ${err}`);
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
  setInterval(search_next_events, BOT_CONFIG.events.interval_get_events);
  setInterval(notify_warn_disconnect_before_leave_schedule, BOT_CONFIG.events.interval_get_events);
  refresh_token();
}

run();