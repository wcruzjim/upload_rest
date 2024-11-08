const axios = require('axios');
const CONFIG = require('../../../config/config');
const general_helper = require('../../../helpers/general')


// get list of automatic_notifications
function get_pending_feedbacks(date_time, api_token){

  return new Promise(function(resolve, reject){
    axios.post(`${CONFIG.integrations.api.url}/Gtr/getPendingFeedbacks`, {
      date_time: date_time
    }, {
      headers: {
        Authorization: api_token
      },
      httpsAgent: new (require('https')).Agent({ rejectUnauthorized: false })
    })
    .then(response => {
      if (response.data == undefined || response.data.result == undefined){
        general_helper.showDetails(response);
        return reject(new Error('Error trying to get pending feedback'));
      }
      resolve(response.data.result);
    })
    .catch((error) => {
      general_helper.showDetails(error);
      reject(new Error('Error trying to get pending feedback'));
    });
  });
}


module.exports = {
    get_pending_feedbacks
}
