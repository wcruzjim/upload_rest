
const tymeshift_model = require("../models/tymeshift_model");
const general_helper = require('../../../helpers/general');



async function get_keymap_documents_for_tymeapp_users(api_token){

    let users = await tymeshift_model.get_documents_for_tymeapp_users(api_token);
    let json_keymap = {};
    
    users.forEach(function(val){
        json_keymap[ val['user_tymeapp'] ] = val['identify'];
    });

    general_helper.showDetails(`[Bot tymeshift] Finished get users,documents TymeApp from jarvis ${users.length}`);
    return json_keymap;
}

async function get_keymap_network_users(api_token){
    
    let users = await tymeshift_model.get_network_users_for_alert_tigo(api_token);
    
    let json_keymap = {};
    
    users.forEach(function(val){
        json_keymap[ val['usermail'] ] = val['networkuser'];
    });

    general_helper.showDetails(`[Bot tymeshift] Finished get network users for Alert Tigo ${users.length}`);
    return json_keymap;
}


async function get_network_users_for_alert_tigo(result_working_users, keymap_network_users_tigo){

    return result_working_users.map(function(val){
        
        let userEmail = "";

        if(val.useremail == undefined){
            userEmail = val.user_zendesk;
        }else{
            userEmail = val.useremail;
        }

        val.first_name = keymap_network_users_tigo[userEmail];
        return val;
    });

}

function link_identify_to_tymeapp_users(working_users, jarvis_users){

    return working_users.map(function(val){

        val.identify = jarvis_users[val['useremail']];
        return val;
    });

}


module.exports = {
    get_keymap_documents_for_tymeapp_users,
    get_keymap_network_users,
    get_network_users_for_alert_tigo,
    link_identify_to_tymeapp_users
}
