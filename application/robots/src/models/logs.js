const CONFIG = require("../config/config");
const general_helper = require('../helpers/general');
const axios = require('axios');

// save group of logs into database
async function save_logs(logs, api_token){

  return new Promise(function(resolve, reject){
    axios.post(`${CONFIG.integrations.api.url}/Gtr/saveLogs`,{
      strictSSL : false,
      json: true,
      data: logs
    }, {
      headers: {
        Authorization: api_token
      }
    })
    .then(response => {
      if (response.data == undefined || response.data.result == undefined){
        general_helper.showDetails(response);
        return reject(new Error('Failure saving general logs'));
      }
      general_helper.showDetails('Success saving general logs');
      resolve(response.data.result);
    })
    .catch((error) => {
      general_helper.showDetails(error);
      reject(new Error('Failure saving general logs'));
    });
  });


}


module.exports = {
  save_logs
};
