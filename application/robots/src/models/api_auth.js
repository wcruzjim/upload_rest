const CONFIG = require('../config/config');
const general_helper = require('../helpers/general');
const axios = require('axios');


function get_access_token(){
  
  return new Promise(function(resolve, reject){
    axios.post(`${CONFIG.integrations.api.url}/seguridad/login`,{
      package2 : CONFIG.integrations.api.user,
      package1 : CONFIG.integrations.api.password
    }, {
      httpsAgent: new (require('https')).Agent({ rejectUnauthorized: false })
    })
    .then(response => {
      if (response.data == undefined || response.data.jwt == undefined){
        general_helper.showDetails(response);
        return reject(new Error('Error triying to access API'));
      }
      resolve(response.data.jwt);
    })
    .catch((error) => {
      general_helper.showDetails(error);
      reject(new Error('Error triying to access API'));
    });
  });
}

module.exports = {
  get_access_token
}
