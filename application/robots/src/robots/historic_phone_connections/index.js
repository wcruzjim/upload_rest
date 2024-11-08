const { get_access_token } = require('../../models/api_auth');
const CONFIG = require('../../config/config');
const phone_connection_model = require("./models/phone_connection_model");
const schedule_model = require("./models/schedule_model");
const general_helper = require('../../helpers/general');
const io = require('socket.io-client');
const redis = require('redis').createClient(CONFIG.integrations.redis);
const socket = io(CONFIG.integrations.automatic_notifications.sockets_host + ':' + CONFIG.integrations.automatic_notifications.sockets_port , { upgrade : false, transports : ['websocket'], rejectUnauthorized : false, query : {source : 'cosmos_worker', name : 'events_notifications'}});
let api_token = '';

redis.on('error', function(){
  general_helper.showDetails('Worker Historic Phone Connections : Redis error');
});
socket.on('connect', function(){
  general_helper.showDetails('Worker Historic Phone Connections : Socket connected');
});
socket.on('error', function(err){
  general_helper.showDetails(`Historic Phone Connections : Socket error ${err}`);
});
socket.on('event', function(){
  general_helper.showDetails('Historic Phone Connections : Socket event');
});
socket.on('disconnect', function(err){
  general_helper.showDetails(`Historic Phone Connections : Socket disconnected ${err}`);
});

// Subscribe to channel worker Historic Phone Connections
redis.subscribe('worker:historic_phone_connections');


// Listen incoming messages
redis.on('message', function(channel, message){

  switch(channel){
    case 'worker:historic_phone_connections' : {
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

// Start to get connections phone to save into database
async function start_collection_connections(){
    try{
        
        console.time("save_interval_connections");
        let result_connectios = await phone_connection_model.generate_historic_interval_connections(api_token);
        console.timeEnd("save_interval_connections");
        
        // If it was successful then link the identify to the login
        if(result_connectios === true){
            console.time("link_identify_interval_connections");
            await phone_connection_model.link_identify_to_login_id(api_token);
            console.timeEnd("link_identify_interval_connections");
        }
    }
    catch(err){
        general_helper.showDetails(`Error collecting phone connections interval ${err}`);
    }
}

// Delete all the phone connections in specific range of time
async function delete_historic_connections(){
    try{
        console.time("delete_interval_connections");
        await phone_connection_model.delete_historic_phone_connections(CONFIG.integrations.historic_phone_connections.data_retention, api_token);
        console.timeEnd("delete_interval_connections");
    }
    catch(err){
        general_helper.showDetails(`Error collecting phone connections interval. ${err}`);
    }
}


async function save_consolidated_adherence(){
  try{
    
    console.time("get_count_current_connections");
    let result_count_connections = await phone_connection_model.count_current_connections(api_token);
    console.timeEnd("get_count_current_connections");
    
    console.time("get_count_current_connection_events");
    let result_schedules = await schedule_model.count_current_connection_events(api_token);
    console.timeEnd("get_count_current_connection_events");
    
    console.time("save_consolidated_phone_connections");
    let result_consolidated = await phone_connection_model.save_consolidated_phone_connections(result_schedules[0].quantity,result_count_connections[0].quantity, api_token);
    console.timeEnd("save_consolidated_phone_connections");


    //  generate adherence just for customer tigo
    console.time("save_consolidated_connected_users_per_pcrc");
    await phone_connection_model.save_consolidated_connected_users_per_pcrc(api_token);
    console.timeEnd("save_consolidated_connected_users_per_pcrc");

    console.time("delete_consolidated_connected_users_per_pcrc");
    await phone_connection_model.delete_consolidated_connected_users_per_pcrc(api_token);
    console.timeEnd("delete_consolidated_connected_users_per_pcrc");



    if(!result_consolidated){
      general_helper.showDetails("Error trying to save interval connections");
    }

  }
  catch(err){
    general_helper.showDetails(`Error trying to save consolidated adherence. ${err}`);
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
  setInterval(start_collection_connections, CONFIG.integrations.historic_phone_connections.interval_collect_data * 1000);
  setInterval(save_consolidated_adherence, CONFIG.integrations.historic_phone_connections.interval_collect_adherence * 1000);
  setInterval(delete_historic_connections, 60000); // Interval to apply data retention
  refresh_token();
}

run();