const CONFIG = require('../config/config');
const notification_log_model = require("../models/notification_logs");
const { get_access_token } = require('../models/api_auth');

// List of global logs
let logs = [];

let api_token = '';

// // Add a log in the queue
function create_log(log){
  if(log == undefined){
    return;
  }
  logs.push(log);
}



// Save the current logs in the queue
async function save_current_logs(){

  if(!CONFIG.integrations.logs.active){
    return;
  }

  // Create exactly copy
  let temp_logs = logs.slice();

  
  if(temp_logs.length < 1){
    return;
  }

  // Clear logs queue
  logs = [];

  temp_logs = temp_logs.map(function(current_log){
    return {
              id_login: current_log.id_login,
              document: current_log.document,
              date: current_log.date,
              skill_internal_code: current_log.skill_internal_code,
              notify_state: current_log.notify_state,
              agent_time: current_log.agent_time,
              title: current_log.notify_title,
              message: current_log.notify_message,
              type: current_log.notify_type,
              automatic_notifications_id: current_log.automatic_notifications_id,
              notifications_json: current_log.notifications_json
          };
  });
  await notification_log_model.save_logs(temp_logs, api_token);

  

}

function refresh_token(){
  setTimeout(async function(){
    api_token = await get_access_token();
    refresh_token()
  },CONFIG.integrations.api.token_refresh_time)  
}


async function automatic_saving_logs(){
  api_token =  await get_access_token();
  setInterval(save_current_logs, CONFIG.integrations.logs.interval_save_logs);
  refresh_token();

}

// Active automatic saving logs
automatic_saving_logs();


module.exports = {
  create_log
}
