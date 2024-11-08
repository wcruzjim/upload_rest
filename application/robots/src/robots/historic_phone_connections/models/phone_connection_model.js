const axios = require('axios');
const CONFIG = require('../../../config/config');
const general_helper = require('../../../helpers/general');


// Generate new segment of connections 
function generate_historic_interval_connections(api_token){


  return new Promise(function(resolve, reject){
    axios.get(`${CONFIG.integrations.api.url}/Gtr/generateHistoricIntervalConnections`,{
      headers: {
        Authorization: api_token
      },
      httpsAgent: new (require('https')).Agent({ rejectUnauthorized: false })
    })
    .then(response => {
      if (response.data == undefined || response.data.result == undefined){
        general_helper.showDetails(response);
        return reject(new Error('Error trying to generate historic interval connections'));
      }
      resolve(true);
    })
    .catch((error) => {
      general_helper.showDetails(error);
      reject(new Error('Error trying to generate historic interval connections'));
    });
  });
}


// Try to link identify to id login 
function link_identify_to_login_id(api_token){

  return new Promise(function(resolve, reject){
    axios.get(`${CONFIG.integrations.api.url}/Gtr/linkIdentifyToLoginId`, {
      headers: {
        Authorization: api_token
      },
      httpsAgent: new (require('https')).Agent({ rejectUnauthorized: false })
    })
    .then(response => {
      if (response.data == undefined || response.data.result == undefined){
        general_helper.showDetails(response);
        return reject(new Error('Error trying to identify to login ID'));
      }
      resolve(response.data.result);
    })
    .catch((error) => {
      general_helper.showDetails(error);
      reject(new Error('Error trying to identify to login ID'));
    });
  });
}


// delete historic connections phone 
function delete_historic_phone_connections(data_retention_interval, api_token){

  return new Promise(function(resolve, reject){
    axios.post(`${CONFIG.integrations.api.url}/Gtr/deleteHistoricPhoneConnections`,{
        data: data_retention_interval
    }, {
      headers: {
        Authorization: api_token
      },
      httpsAgent: new (require('https')).Agent({ rejectUnauthorized: false })
    })
    .then(response => {
      if (response.data == undefined || response.data.result == undefined || response.data.result < 0){
        general_helper.showDetails(response);
        return reject(new Error('Error trying to delete historic phone connections'));
      }
      resolve(true);
    })
    .catch((error) => {
      general_helper.showDetails(error);
      reject(new Error('Error trying to delete historic phone connections'));
    });
  });
}




// get count current phone connections 
function count_current_connections(api_token){

  return new Promise(function(resolve, reject){
    axios.get(`${CONFIG.integrations.api.url}/Gtr/countCurrentConnections`, {
      headers: {
        Authorization: api_token
      },
      httpsAgent: new (require('https')).Agent({ rejectUnauthorized: false })
    })
    .then(response => {
      if (response.data == undefined || response.data.result == undefined){
        general_helper.showDetails(response);
        return reject(new Error('Error trying to get current connections count'));
      }
      resolve(response.data.result);
    })
    .catch((error) => {
      general_helper.showDetails(error);
      reject(new Error('Error trying to get current connections count'));
    });
  });
}


// save consolidated phone connections 
function save_consolidated_phone_connections(schedules, phone_connections, api_token){

  let data = {
    schedules,
    phone_connections
  }

  return new Promise(function(resolve, reject){
    axios.post(`${CONFIG.integrations.api.url}/Gtr/saveConsolidatedPhoneConnections`,{
        data
    }, {
      headers: {
        Authorization: api_token
      },
      httpsAgent: new (require('https')).Agent({ rejectUnauthorized: false })
    })
    .then(response => {
      if (response.data == undefined || response.data.result == undefined || response.data.result < 0){
        general_helper.showDetails(response);
        return reject(new Error('Error trying to save consolidated phone connections'));
      }
      resolve(true);
    })
    .catch((error) => {
      general_helper.showDetails(error);
      reject(new Error('Error trying to save consolidated phone connections'));
    });
  });
}




function save_consolidated_connected_users_per_pcrc(api_token){

  return new Promise(function(resolve, reject){
    axios.get(`${CONFIG.integrations.api.url}/Gtr/saveConsolidatedConnectedUsersPerPcrc`,{
      headers: {
        Authorization: api_token
      },
      httpsAgent: new (require('https')).Agent({ rejectUnauthorized: false })
    })
    .then(response => {
      if (response.data == undefined || response.data.result == undefined || response.data.result < 0 ){
        general_helper.showDetails(response);
        return reject(new Error('Error trying to save consolidated connected users per pcrc'));
      }
      resolve(true);
    })
    .catch((error) => {
      general_helper.showDetails(error);
      reject(new Error('Error trying to save consolidated connected users per pcrc'));
    });
  });
  
}



// save consolidated phone connections 
function delete_consolidated_connected_users_per_pcrc(api_token){

  return new Promise(function(resolve, reject){
    axios.get(`${CONFIG.integrations.api.url}/Gtr/deleteConsolidatedConnectedUsersPerPcrc`,{
      headers: {
        Authorization: api_token
      },
      httpsAgent: new (require('https')).Agent({ rejectUnauthorized: false })
    })
    .then(response => {
      if (response.data == undefined || response.data.result == undefined || response.data.result < 0 ){
        general_helper.showDetails(response);
        return reject(new Error('Error trying to delete consolidated connected users per pcrc'));
      }
      resolve(true);
    })
    .catch((error) => {
      general_helper.showDetails(error);
      reject(new Error('Error trying to delete consolidated connected users per pcrc'));
    });
  });
  
}



module.exports = {
  generate_historic_interval_connections,
  link_identify_to_login_id,
  delete_historic_phone_connections,
  count_current_connections,
  save_consolidated_phone_connections,
  save_consolidated_connected_users_per_pcrc,
  delete_consolidated_connected_users_per_pcrc
}
