const axios = require('axios');
const CONFIG = require('../../../config/config');
const general_helper = require('../../../helpers/general')



// get list of automatic_notifications
function get_next_events(time_unix, events, api_token){

  let events_to_notify = events.join(',');

  return new Promise(function(resolve, reject){
    axios.post(`${CONFIG.integrations.api.url}/Gtr/getNextEvents`, {
        data: {
          time_unix,
          events_to_notify
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
        return reject(new Error('Error trying to get next events'));
      }
      resolve(response.data.result);
    })
    .catch((error) => {
      general_helper.showDetails(error);
      reject(new Error('Error trying to get next events'));
    });
  });
}


function get_next_pending_finish_schedules(time_unix, api_token){

  return new Promise(function(resolve, reject){
    axios.post(`${CONFIG.integrations.api.url}/Gtr/getNextPendingFinishSchedules`, {
      data: time_unix
    }, {
      headers: {
        Authorization: api_token
      },
      httpsAgent: new (require('https')).Agent({ rejectUnauthorized: false })
    })
    .then(response => {
      if (response.data == undefined || response.data.result == undefined){
        general_helper.showDetails(response);
        return reject(new Error('Error trying to get next pending finish schedules'));
      }
      resolve(response.data.result);
    })
    .catch((error) => {
      general_helper.showDetails(error);
      reject(new Error('Error trying to get next pending finish schedules'));
    });
});

}



module.exports = {
    get_next_events,
    get_next_pending_finish_schedules
}
