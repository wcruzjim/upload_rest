const axios = require('axios');
const CONFIG = require('../../../config/config');
const general_helper = require('../../../helpers/general');


// // get list of automatic_notifications
function count_current_connection_events(api_token){

  return new Promise(function(resolve, reject){
    axios.get(`${CONFIG.integrations.api.url}/Gtr/countCurrentConnectionEvents`,{
      headers: {
        Authorization: api_token
      },
      httpsAgent: new (require('https')).Agent({ rejectUnauthorized: false })
    })
    .then(response => {
      if (response.data == undefined || response.data.result == undefined){
        general_helper.showDetails(response);
        return reject(new Error('Error trying to get current connection events count'));
      }
      resolve(response.data.result);
    })
    .catch((error) => {
      general_helper.showDetails(error);
      reject(new Error('Error trying to get current connection events count'));
    });
  });
}


module.exports = {
    count_current_connection_events
}
