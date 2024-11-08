const axios = require('axios');
const CONFIG = require('../../../config/config');
const general_helper = require('../../../helpers/general');
const teo_rest_communication = require('../../../models/api_connection_map');
const BOT_CONFIG = require('../config/config');
const formData = require('form-data');
const qs = require('qs');


// Truncate table to flush innodb cache
function get_authentication_cookies(integration_credentials){
  let data = new formData()
  data.append('username', integration_credentials.user);
  data.append('password', integration_credentials.password);
  data.append('zend_acc', BOT_CONFIG.tigo_tymeshift.zend_acc);
  data.append('update_token', BOT_CONFIG.tigo_tymeshift.update_token);
  return new Promise(function(resolve, reject){
    axios.post(`${BOT_CONFIG.tigo_tymeshift.api_url_login}/remoteLogin`,data)
    .then(response => {
      if (response.data == undefined || response.data.result == undefined){
        general_helper.showDetails(response);
        return reject(new Error('Error'));
      }
      const authentication_cookies = set_authentication_cookies(response.headers['set-cookie']);
      resolve(authentication_cookies[0]);
    })
    .catch((error) => {
      general_helper.showDetails(error);
      reject(new Error('Error'));
    });
  });

}

function format_integration_credentials(integration_credentials){
  return integration_credentials.map(credentials => {
     return {
      integration_id:credentials.integration_id,
      user:credentials.user,
      password:credentials.password
     } 
  });
}

function set_authentication_cookies(cookies){
  return cookies.filter(cookie => cookie.includes(BOT_CONFIG.tigo_tymeshift.cookie_name));
}

// Truncate table to flush innodb cache
function get_working_users(cookies){

  let filters = BOT_CONFIG.tigo_tymeshift.config_request_who_is_working;
  let data = Object.keys(filters)
    .map(key => `${encodeURIComponent(key)}=${encodeURIComponent(filters[key])}`)
    .join('&');

  let config = {
    method: 'post',
    maxBodyLength: Infinity,
    url: `${BOT_CONFIG.tigo_tymeshift.api_url_customer}/AjaxGetWhoisworking`,
    headers: { 
      'Content-Type': 'application/x-www-form-urlencoded', 
      'Cookie': cookies
    },
    data : data
  };

  return new Promise(function(resolve, reject){

    axios.request(config)
    .then((response) => {
      if (response.data == undefined || response.data.result == undefined || response.data.result.modelZU == undefined){
        general_helper.showDetails(response);
        return reject(new Error("[Bot tymeshift] Error trying to consume API AjaxGetWhoisworking "));
      }

      resolve(response.data.result.modelZU);
    })
    .catch((error) => {
      general_helper.showDetails(error);
      reject(new Error("[Bot tymeshift] Error trying to consume API AjaxGetWhoisworking "));
    });
  })

}


// Truncate table to flush innodb cache
async function save_working_users(data, api_token){

  let tmp_data = data.map(function(val){
    return {
        id: val.id,
        username: val.username == undefined ? null : val.username,
        useremail: val.useremail == undefined ? null : val.useremail,
        user_id: val.user_id == undefined ? null : val.user_id,
        photo: val.photo == undefined ? null : val.photo,
        channel: val.channel == undefined ? null : val.channel,
        requester_username: val.requester_username == undefined ? null : val.requester_username,
        jobname: val.jobname == undefined ? null : val.jobname,
        is_working: val.is_working == undefined ? null : val.is_working,
        jobtime: val.jobtime == undefined ? null : val.jobtime,
        jobtimetxt: val.jobtimetxt == undefined ? null : val.jobtimetxt,
        identify: val.identify == undefined ? null : val.identify
    };
  });

  general_helper.showDetails('[Bot tymeshift] Rows to save into tymeshift_working_users: ' + tmp_data.length);


  return new Promise(function(resolve, reject){
    axios.post(`${CONFIG.integrations.api.url}/Gtr/saveTymeshiftWorkingUsers`,{
      data: tmp_data
    }, {
      headers: {
        Authorization: api_token
      },
      httpsAgent: new (require('https')).Agent({ rejectUnauthorized: false })
    })
    .then(response => {
      if (response.data == undefined || response.data.result == undefined){
        general_helper.showDetails(response);
        return reject(new Error('Error trying to save tymeshift_working_users'));
      }
      resolve(response.data.result);
    })
    .catch((error) => {
      general_helper.showDetails(error);
      reject(new Error('Error trying to save tymeshift_working_users'));
    });
  });
}


// Truncate table to flush innodb cache


function delete_working_users(api_token) {

  return new Promise(function(resolve, reject){
    axios.delete(`${CONFIG.integrations.api.url}/Gtr/deleteTymeshiftWorkingUsers`,{
      headers: {
        Authorization: api_token
      },
      httpsAgent: new (require('https')).Agent({ rejectUnauthorized: false })
    })
    .then(response => {
      if (response.data == undefined || response.data.result == undefined){
      general_helper.showDetails(response);
        return reject(new Error('Error trying to delete Tymeshift Working Users'));
      }
      resolve(response.data.result);
    })
    .catch((error) => {
      general_helper.showDetails(error);
      reject(new Error('Error trying to delete Tymeshift Working Users'));
    });

  });
}


// Truncate table to flush innodb cache

function get_documents_for_tymeapp_users(api_token){

  return new Promise(function(resolve, reject){
    axios.get(`${CONFIG.integrations.api.url}/Gtr/getDocumentsForTymeappUsers`,{
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


function get_tickets_working_users(api_token){
    general_helper.showDetails('[Bot Zendesk] Start getting tickets of tymeapp working users');


    return new Promise(function(resolve, reject){
      axios.get(`${CONFIG.integrations.api.url}/Gtr/getWorkingUserTickets`,{
        headers: {
          Authorization: api_token
        },
        httpsAgent: new (require('https')).Agent({ rejectUnauthorized: false })
      })
      .then(response => {
        if (response.data == undefined || response.data.result == undefined){
          general_helper.showDetails(response);
          return reject(new Error('Error trying to get tickets of tymeapp working users'));
        }
        resolve(response.data.result);
      })
      .catch((error) => {
        general_helper.showDetails(error);
        reject(new Error('Error trying to get tickets of tymeapp working users'));
      });
    });
}



function get_detail_tickets_by_id(tickets_id){

  general_helper.showDetails('[Bot Zendesk] Get detail tickets by ID');

  if(tickets_id.constructor !== Array || tickets_id.length < 1){
    return [];
  }

  let email_auth = BOT_CONFIG.zendesk.user
  let password_auth = BOT_CONFIG.zendesk.password


  return new Promise(function(resolve, reject){

    axios.get(`${BOT_CONFIG.zendesk.url}tickets/show_many.json?ids=${tickets_id.join(',')}&include=slas`,{
      headers: {
        Authorization :  'Basic ' +  Buffer.from( email_auth + ':' + password_auth ).toString("base64"),
        'Content-Type' : 'application/json',
        'Accept' : 'application/json'
      },
      params: {
        strictSSL : false,
        json: true,
        timeout : 80000
      }
    })
    .then(response => {
      if (response.data == undefined || response.data.tickets == undefined){
        general_helper.showDetails(response);
        return reject(new Error('Error trying to get tickets by id'));
      }
      resolve(response.data);
    })
    .catch((error) => {
      general_helper.showDetails(error);
      reject(new Error('Error trying to get tickets by id'));
    });
  });
  

}

function format_detail_user_tickets(data){

  return data.map(function(val){
    return {
        id_ticket: val.id_ticket,
        user_zendesk: val.user_zendesk,
        identify: val.identify,
        format_frt: val.format_frt,
        frt: val.frt,
        frt_minutes: val.frt_minutes,
        stage_frt: val.stage_frt,
        format_nrt: val.format_nrt,
        nrt: val.nrt,
        nrt_minutes: val.nrt_minutes,
        stage_nrt: val.stage_nrt,
        channel: val.channel,
        status: val.status,
        type: val.type,
        priority: val.priority,
        requester_id: val.requester_id,
        submitter_id: val.submitter_id,
        assignee_id: val.assignee_id,
        satisfaction_rating: val.satisfaction_rating,
        created_at: val.created_at,
        update_at: val.update_at
    };
});

}



async function save_user_tickets(data, api_token){

  general_helper.showDetails('[Bot Zendesk] Save user tickets. rows : ' + (data.length));
  let tmp_data = format_detail_user_tickets(data);
  general_helper.showDetails('[Bot Zendesk] Rows to save : ' + tmp_data.length);

  return new Promise(function(resolve, reject){
    axios.post(`${CONFIG.integrations.api.url}/Gtr/saveTymeshiftTicketsUser`,{
      data: tmp_data
    }, {
      headers: {
        Authorization: api_token
      },
      httpsAgent: new (require('https')).Agent({ rejectUnauthorized: false })
    })
    .then(response => {
      if (response.data == undefined || response.data.result == undefined){
        general_helper.showDetails(response);
        return reject(new Error('Error trying to save user_tickets'));
      }
      resolve(response.data.result);
    })
    .catch((error) => {
      general_helper.showDetails(error);
      reject(new Error('Error trying to save user_tickets'));
    });
  });


}


function delete_old_user_tickets(api_token){
    general_helper.showDetails('[Bot Zendesk] Delete old rows');
    let retention_hours = BOT_CONFIG.tigo_tymeshift.zendesk_retention_ticket_hours;
    
    return new Promise(function(resolve, reject){
      axios.delete(`${CONFIG.integrations.api.url}/Gtr/deleteUserTickets/${retention_hours}`,{
        headers: {
          Authorization: api_token
        },
        httpsAgent: new (require('https')).Agent({ rejectUnauthorized: false })
      })
      .then(response => {
        if (response.data == undefined || response.data.result == undefined){
        general_helper.showDetails(response);
          return reject(new Error('Error trying to delete user tickets'));
        }
        resolve(response.data.result);
      })
      .catch((error) => {
        general_helper.showDetails(error);
        reject(new Error('Error trying to delete user tickets'));
      });
  
    });
}

function get_network_users_for_alert_tigo(api_token){

  return new Promise(function(resolve, reject){
    axios.get(`${CONFIG.integrations.api.url}/Gtr/getNetworUsersForAlertTigo`,{
      headers: {
        Authorization: api_token
      },
      httpsAgent: new (require('https')).Agent({ rejectUnauthorized: false })
    })
    .then(response => {
      if (response.data == undefined || response.data.result == undefined){
        general_helper.showDetails(response);
        return reject(new Error('Error trying to get network users for alert Tigo'));
      }
      resolve(response.data.result);
    })
    .catch((error) => {
      general_helper.showDetails(error);
      reject(new Error('Error trying to get network users for alert Tigo'));
    });
  });
}



function get_tigo_unique_fingerprint(filter, api_token){
  
  let where_value="";

  if(filter == 1){
    where_value = "zendesk status";
  }else{
    where_value = "zendesk NRT";
  }

  return new Promise(function(resolve, reject){
    axios.post(`${CONFIG.integrations.api.url}/Gtr/getTigoUniqueFingerprint`,{
      data: where_value
    }, {
      headers: {
        Authorization: api_token
      },
      httpsAgent: new (require('https')).Agent({ rejectUnauthorized: false })
    })
    .then(response => {
      if (response.data == undefined || response.data.result == undefined){
        general_helper.showDetails(response);
        return reject(new Error('Error trying to get Tigo unique fingerprint'));
      }
      resolve(response.data.result);
    })
    .catch((error) => {
      general_helper.showDetails(error);
      reject(new Error('Error trying to get Tigo unique fingerprint'));
    });
  });
}



function format_connections_map(data, integration_id){

  const random_fingerprint = Math.floor(general_helper.randomSecure() * 10000000000);
  
  return data.map(function(val){
      return {
        skill: val.skill = "zendesk_1",
        first_name: val.first_name,
        id_login: val.id_login = null,
        extension: val.extension = null,
        state: val.jobname == undefined ? "NRT" : val.jobname,
        current_skill: val.current_skill = "zendesk_1",
        time: val.jobtime == undefined ? (val.nrt_minutes * -1) * 60 : val.jobtime,
        priority: val.priority = null,
        finger_print: val.finger_print = integration_id,
        platform: val.jobname == undefined ? "zendesk NRT" : "zendesk status",
        unique_finger_print: val.unique_finger_print = random_fingerprint
      };
  });

}

function get_tickets_by_view(zendesk_view){

  let email_auth = BOT_CONFIG.zendesk.user;
  let password_auth = BOT_CONFIG.zendesk.password;


  return new Promise(function(resolve, reject){

    axios.get(`${BOT_CONFIG.zendesk.url}/views/${zendesk_view.zendesk_view}/execute.json?per_page=1000`,{
      headers: {
        Authorization :  'Basic ' +  Buffer.from( email_auth + ':' + password_auth ).toString("base64"),
        'Content-Type' : 'application/json',
        'Accept' : 'application/json'
      },
      params: {
        strictSSL : false,
        json: true,
        timeout : 80000
      }
    })
    .then(response => {
      if (response.data == undefined || response.data.rows == undefined){
        general_helper.showDetails(response);
        return reject(new Error('Error trying to get tickets by view'));
      }
      let formated_result = response.data.rows.map(function(val){
        return {  
                        ticket_id : val.ticket_id, 
                        created_at : val.created, 
                        cod_pcrc : zendesk_view.cod_pcrc 
                     };
      });
      resolve(formated_result);
    })
    .catch((error) => {
      general_helper.showDetails(error);
      reject(new Error('Error trying to get tickets by view'));
    });
  });

}

function format_len_number(number_value){
  let current_value = parseInt(number_value);
  if(isNaN(current_value)){
    return number_value;
  }

  if(current_value < 10){
    return '0' + current_value;
  }
 return current_value;
}

function time_iso_to_utc(date_value){
  if(date_value == undefined || date_value == null){
    return null;
  }
  let new_date = new Date(date_value);
  let year = format_len_number(new_date.getFullYear());
  let month = format_len_number(new_date.getMonth()+1);
  let day = format_len_number(new_date.getDate());
  let hours = format_len_number(new_date.getHours());
  let minutes = format_len_number(new_date.getMinutes());
  let seconds = format_len_number(new_date.getSeconds());
  return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`
}


function save_zendesk_tickets_history(data, api_token){
  
  if(data === undefined || data.constructor  !== Array){
    return Promise.reject('Invalid data to save into tymeshift_tickets_history');
  }
  if(data.length < 1){
    return Promise.resolve(true);
  }
  
  let tmp_data = data.map(function(val){
    return { 
              id:val.ticket_id,
              created_at: time_iso_to_utc(val.created_at),
              cod_pcrc: val.cod_pcrc
          };
  });

  return new Promise(function(resolve, reject){
    axios.post(`${CONFIG.integrations.api.url}/Gtr/saveZendeskTicketHistory`,{
      data: tmp_data
    }, {
      headers: {
        Authorization: api_token
      },
      httpsAgent: new (require('https')).Agent({ rejectUnauthorized: false })
    })
    .then(response => {
      if (response.data == undefined || response.data.result == undefined){
        general_helper.showDetails(response);
        return reject(new Error('Error trying to save Zendesk ticket history'));
      }
      resolve(response.data.result);
    })
    .catch((error) => {
      general_helper.showDetails(error);
      reject(new Error('Error trying to save Zendesk ticket history'));
    });
  });
}

function get_zendesk_views_pcrc(api_token){

  return new Promise(function(resolve, reject){
    axios.get(`${CONFIG.integrations.api.url}/Gtr/getZendeskViewsPcrc`,{
      headers: {
        Authorization: api_token
      },
      httpsAgent: new (require('https')).Agent({ rejectUnauthorized: false })
    })
    .then(response => {
      if (response.data == undefined || response.data.result == undefined){
        general_helper.showDetails(response);
        return reject(new Error('Error trying to get Zendesk views PCRC'));
      }
      resolve(response.data.result);
    })
    .catch((error) => {
      general_helper.showDetails(error);
      reject(new Error('Error trying to get Zendesk views PCRC'));
    });
  });

}


module.exports = {
    save_working_users,
    delete_working_users,
    get_authentication_cookies,
    get_working_users,
    get_documents_for_tymeapp_users,
    get_tickets_working_users,
    get_detail_tickets_by_id,
    save_user_tickets,
    delete_old_user_tickets,
    get_network_users_for_alert_tigo,
    get_tigo_unique_fingerprint,
    format_connections_map,
    get_tickets_by_view,
    save_zendesk_tickets_history,
    get_zendesk_views_pcrc,
    format_integration_credentials
}
