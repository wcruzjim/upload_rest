const CONFIG = require("../config/config");
const general_helper = require('../helpers/general');
const axios = require('axios');

// save group of logs into database
function save_logs(logs, api_token){

  return new Promise(function(resolve, reject){
    axios.post(`${CONFIG.integrations.api.url}/Gtr/saveNotificationLogs`,{
      data: logs
    }, {
      headers: {
        Authorization: api_token
      },
      httpsAgent: new (require('https')).Agent({ rejectUnauthorized: false })
    })
    .then(response => {
      if (response.data == undefined || response.data.result == undefined){
        general_helper.showDetails(response);
        return reject(new Error('Error trying to save notification logs'));
      }
      general_helper.showDetails('Success saving notification logs');
      resolve(response.data.result);
    })
    .catch((error) => {
      general_helper.showDetails(error);
      reject(new Error('Error trying to save notification logs'));
    });
  });

}


function get_documents(api_token){

  return new Promise(function(resolve, reject){
    axios.get(`${CONFIG.integrations.api.url}/Gtr/getDocuments`,{
      headers: {
        Authorization: api_token
      },
      httpsAgent: new (require('https')).Agent({ rejectUnauthorized: false })
    })
    .then(response => {
      if (response.data == undefined || response.data.result == undefined){
        general_helper.showDetails(response);
        return reject(new Error('Error trying to get documents'));
      }
      resolve(response.data.result);
    })
    .catch((error) => {
      general_helper.showDetails(error);
      reject(new Error('Error trying to get documents'));
    });
  });

}


module.exports = {
  save_logs,
  get_documents
};
