const tymeshift_model = require("./models/tymeshift_model");
const helper_logs = require("../../helpers/logs.js");
const helper_users = require("./helpers/users");
const helper_tickets = require("./helpers/tickets");
const general_helper = require('../../helpers/general');
const teo_rest_communication = require('../../models/api_connection_map');
const { get_access_token } = require('../../models/api_auth');
const BOT_CONFIG = require('./config/config');
const CONFIG = require('../../config/config');
let api_token = '';
let cookies = '';
let integration_type_id = 5;

let keymap_tymeapp_users = {};
let keymap_network_users_tigo = {};

async function get_and_save_tymeshift_users(interval_time, integration_id){
    general_helper.showDetails("[Bot tymeshift]  Start getting data get_and_save_tymeshift_users");
    try{
        general_helper.showDetails('[Bot tymeshift] Start get working users from TymeApp');
        let result_working_users = await tymeshift_model.get_working_users(cookies);
        general_helper.showDetails("[Bot tymeshift] Finished get working users from TymeApp");
        if(result_working_users.constructor === Array){
            general_helper.showDetails('[Bot tymeshift] Start delete working users from cosmos DB');
            await tymeshift_model.delete_working_users(api_token);
            general_helper.showDetails('[Bot tymeshift] Finished delete working users from cosmos DB');
        }
        general_helper.showDetails('[Bot tymeshift] Start save working users into cosmos DB');
        result_working_users = helper_users.link_identify_to_tymeapp_users(result_working_users, keymap_tymeapp_users);
        let result_save_working_users = await tymeshift_model.save_working_users(result_working_users, api_token);
        general_helper.showDetails(`[Bot tymeshift] Finished save working users into db: ${result_save_working_users}`);
        


        let id_unique_finger = await tymeshift_model.get_tigo_unique_fingerprint(1, api_token);
        
        let id_unique = id_unique_finger.map(function(val){  return val.id });
        
        const data_connection_map = await helper_users.get_network_users_for_alert_tigo(result_working_users, keymap_network_users_tigo);
        
        const data_filter_connection = data_connection_map.filter(function (values) {
    
            return values.is_working == '1' && (values.useremail.includes('@grupokonecta') || values.useremail.includes('@tigo.net.bo') || values.useremail.includes('@xperience.net.co') ) && (values.jobname.includes('Untracked') || values.jobname.includes('Break')) && values.first_name != null;
        }); 
        const formatted_connections_map = tymeshift_model.format_connections_map(data_filter_connection, integration_id);

        let result_save_connections_map = await teo_rest_communication.save_chunk(formatted_connections_map);

        general_helper.showDetails(`[Bot tymeshift] Finished save connections_map into db : ${result_save_connections_map}`);
        if(id_unique.length > 0){
            await delete_old_connection_map(parseInt(id_unique[0]));
            general_helper.showDetails('[Bot tymeshift] Delete old rows alerts STATUS');
        }
        helper_logs.create_log(11, result_working_users.length, 'debug', 'worker_tymeapp');
        
        general_helper.showDetails('[Bot tymeshift] Sleep 3 seconds before start again');
        delay_function(get_and_save_tymeshift_users, interval_time);
    }
    catch(err){
        delay_function(get_and_save_tymeshift_users, 5000);
        general_helper.showDetails('[Bot tymeshift] Exception caught. Sleeping 5 seconds before try again');
        general_helper.showDetails(`[Bot tymeshift] Error : ${err}`);
    }
}

async function delete_old_connection_map(last_random_fingerprint) {
    await teo_rest_communication.delete_data(last_random_fingerprint);
  }

async function get_and_save_user_tickets(interval_time, integration_id){

    general_helper.showDetails("[Bot Zendesk]  Start getting data get_and_save_user_tickets");
    try{

        let working_tickets = await tymeshift_model.get_tickets_working_users(api_token);
        working_tickets = helper_tickets.format_jobnames_to_tickets(working_tickets);
        
        let tickets_id = working_tickets.map(function(val){ return val.ticket_id });
        let detail_tickets = await tymeshift_model.get_detail_tickets_by_id(tickets_id);

    
        detail_tickets = helper_tickets.format_detail_tickets(detail_tickets.tickets, working_tickets, keymap_tymeapp_users);

        await tymeshift_model.save_user_tickets(detail_tickets, api_token);

        await tymeshift_model.delete_old_user_tickets(api_token);

        let id_unique_finger = await tymeshift_model.get_tigo_unique_fingerprint(2, api_token);
        let id_unique = id_unique_finger.map(function(val){ return val.id });

        const data_connection_map = await helper_users.get_network_users_for_alert_tigo(detail_tickets, keymap_network_users_tigo);
        
        const data_filter_connection = data_connection_map.filter(function (values) {
    
            return values.nrt_minutes < 0 && values.nrt_minutes != null && values.first_name != null;
        }); 

        const formatted_connections_map = tymeshift_model.format_connections_map(data_filter_connection, integration_id);

        await teo_rest_communication.send_chunk(formatted_connections_map);
        
        if(id_unique.length > 0){
            await delete_old_connection_map(parseInt(id_unique[0]));
        }
        general_helper.showDetails('[Bot Zendesk] Sleep some seconds before to start again');
        delay_function(get_and_save_user_tickets, interval_time);
    }
    catch(err){
        delay_function(get_and_save_user_tickets, interval_time);
        general_helper.showDetails('[Bot Zendesk] Exception caught. Sleeping 10 seconds before try again');
        general_helper.showDetails(`[Bot Zendesk] Error : ${err}`);
    }
}



async function get_keymap_users_kmail(){

    general_helper.showDetails('[Bot Zendesk] Get keymap users KMAIL');
    keymap_tymeapp_users = await helper_users.get_keymap_documents_for_tymeapp_users(api_token);
    general_helper.showDetails('[Bot Zendesk] Finish get keymap users KMAIL');

    delay_function(get_keymap_users_kmail, 60000);
}

async function get_keymap_network_users_tigo(){

    general_helper.showDetails('[Bot Zendesk] Get keymap network users KMAIL');
    keymap_network_users_tigo = await helper_users.get_keymap_network_users(api_token);
    general_helper.showDetails('[Bot Zendesk] Finish get keymap network users KMAIL');
    
    delay_function(get_keymap_users_kmail, 60000);
}


function delay_function(fn, interval_time){
    setTimeout(function(){
        fn(interval_time);
    }, interval_time);
}

function refresh_token(){
    setTimeout(async function(){
      api_token = await get_access_token();
      refresh_token()
    },CONFIG.integrations.api.token_refresh_time)  
  }


async function start_all_process(){

    api_token = await get_access_token();
    const integration_credential_list = await teo_rest_communication.get_integration_credential_list(integration_type_id, api_token);
    const formatted_integration_credential_list = tymeshift_model.format_integration_credentials(integration_credential_list);
    cookies = await tymeshift_model.get_authentication_cookies(formatted_integration_credential_list[0]);
    
    await get_keymap_users_kmail();

    await get_keymap_network_users_tigo();
    
    get_and_save_tymeshift_users(BOT_CONFIG.tigo_tymeshift.interval_get_and_save_tymeshift_users, formatted_integration_credential_list[0].integration_id);
    
    get_and_save_user_tickets(BOT_CONFIG.tigo_tymeshift.interval_get_and_save_user_tickets, formatted_integration_credential_list[0].integration_id);

    refresh_token();
}


start_all_process();





