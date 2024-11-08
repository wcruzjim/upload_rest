const CONFIG = require('../config/config');
const logs_model = require("../models/logs");
const { get_access_token } = require('../models/api_auth');


// List of global logs
let logs = [];
let api_token = '';


// Add a log in the queue
function create_log(type_log, log, level, user){

  if(type_log == undefined || log == undefined || level == undefined){
    return;
  }

  if(user == undefined){
    user = "";
  }

  logs.push({ type_log, log, level, user });
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
              type_log: current_log.type_log,
              log: JSON.stringify(current_log.log),
              level: current_log.level,
              user: current_log.user
          };
  });

  await logs_model.save_logs(temp_logs, api_token);
  
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
