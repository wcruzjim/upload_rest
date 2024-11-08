const axios = require('axios');
const CONFIG = require('../../../config/config');
const general_helper = require('../../../helpers/general')


// get list of automatic_notifications
function get_automatic_notifications(api_token){

  return new Promise(function(resolve, reject){
    axios.get(`${CONFIG.integrations.api.url}/Gtr/getAutomaticNotifications`,{
      headers: {
        Authorization: api_token
      },
      httpsAgent: new (require('https')).Agent({ rejectUnauthorized: false })
    })
    .then(response => {
      if (response.data == undefined || response.data.result == undefined){
        general_helper.showDetails(response);
        return reject(new Error('Error trying to get automatic notifications'));
      }
      resolve(response.data.result);
    })
    .catch((error) => {
      general_helper.showDetails(error);
      reject(new Error('Error trying to get automatic notifications'));
    });
  });
}

function getPhonePlatformUsers(api_token){
  return new Promise(function(resolve, reject){
    axios.get(`${CONFIG.integrations.api.url}/Gtr/getPhonePlatformUsers`,{
      headers: {
        Authorization: api_token
      },
      httpsAgent: new (require('https')).Agent({ rejectUnauthorized: false })
    })
    .then(response => {
      if (response.data == undefined || response.data.result == undefined){
        general_helper.showDetails(response);
        return reject(new Error('Error trying to get users uniques'));
      }
      resolve(response.data.result);
    })
    .catch((error) => {
      general_helper.showDetails(error);
      reject(new Error('Error trying to get users uniques'));
    });
  });
}


// // get csr connections from db
function get_phone_connections(api_token){

  let time_start = Date.now();

  return new Promise(function(resolve, reject){
    axios.get(`${CONFIG.integrations.api.url}/Gtr/getPhoneConnections`,{
      headers: {
        Authorization: api_token
      },
      httpsAgent: new (require('https')).Agent({ rejectUnauthorized: false })
    })
    .then(response => {
      if (response.data == undefined || response.data.result == undefined){
        general_helper.showDetails(response);
        return reject(new Error('Error trying to get phone connections'));
      }
      general_helper.showDetails(`Loaded phone connections: ${response.data.result.length}  Time: ${(Date.now() - time_start) / 1000}s`);
      resolve(response.data.result);
    })
    .catch((error) => {
      general_helper.showDetails(error);
      reject(new Error('Error trying to get phone connections'));
    });
  });
}





// clear old automatic notification logs
function clear_automatic_notification_logs(hours_storage_notification_logs, automatic_notifications_type_log, api_token){
  
  return new Promise(function(resolve, reject){
    axios.post(`${CONFIG.integrations.api.url}/Gtr/clearAutomaticNotificationLogs`,{
      data: {
        hours_storage_notification_logs,
        automatic_notifications_type_log
      }
    }, {
      headers: {
        Authorization: api_token
      },
      httpsAgent: new (require('https')).Agent({ rejectUnauthorized: false })
    })
    .then(response => {
      if (response.data == undefined || response.data.result == undefined){
        general_helper.showDetails(response);
        return reject(new Error('Error trying to clear automatic notification logs'));
      }
      general_helper.showDetails('successfully deleted logs');
      resolve(response.data.result);
    })
    .catch((error) => {
      general_helper.showDetails(error);
      reject(new Error('Error trying to clear automatic notification logs'));
    });
  });
}





module.exports = {
  get_automatic_notifications,
  get_phone_connections,
  getPhonePlatformUsers,
  clear_automatic_notification_logs
}
