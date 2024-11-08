require('dotenv').config({path:"../../../../.env"})

var config = {

  tigo_tymeshift : {
    cookie_name: process.env.TIGO_TYMESHIFT_COOKIE_NAME,
    interval_get_and_save_tymeshift_users : 3000,
    interval_get_and_save_user_tickets : 60000,
    api_url_login : process.env.TIGO_TYMESHIFT_API_URL_LOGIN,
    api_url_customer : process.env.TIGO_TYMESHIFT_API_URL_CUSTOMER,
    mail_user : process.env.TIGO_TYMESHIFT_MAIL_USER,
    pass_user : process.env.TIGO_TYMESHIFT_PASSWORD_USER,
    zend_acc : process.env.ZEN_ACC,
    update_token: '0',
    config_request_who_is_working : {
      zend_requester : process.env.ZEND_REQUESTER,
      zend : 1,
      page : 1,
      ww_filter_ticket : 1,
      ww_filter_gentask : 1,
      ww_filter_untracked : 1,
      ww_filter_clockedout : 1,
      ww_filter_chat : 1,
      ww_filter_voice : 0,
      ww_sort : 0,
      group_id : 0,
      itemperpage : 4000,
      selectedAgentView : 0
    },
    zendesk_retention_ticket_hours : 168
  },
  zendesk : {
    user: process.env.ZENDESK_USER,
    password: process.env.ZENDESK_PASSWORD,
    url:process.env.ZENDESK_URL
  }
}

module.exports = config;


