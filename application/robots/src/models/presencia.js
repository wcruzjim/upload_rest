const CONFIG = require("../config/config");
const general_helper = require('../helpers/general');
const axios = require('axios');

function save_presence(list_presence, api_token){
    return new Promise(function(resolve, reject){
        axios.post(`${CONFIG.integrations.api.url}/pcUsage/set_pc_usage_detail`,{
            users: list_presence
        },
        {
            headers: {
                Authorization: api_token
            }
        })
        .then(response => {
            if (response.data == undefined || response.data.result == undefined){
                general_helper.showDetails(response);
                return reject(new Error('Error trying to insert presence'));
            }
            resolve(response.data.result);
        })
        .catch((error) => {
            general_helper.showDetails(error);
            reject(new Error('Error trying to insert presence 1'));
        });
    });
}

module.exports = {
  save_presence
};