const tymeshift_model = require("../tigo_tymeshift/models/tymeshift_model");
const CONFIG = require('../../config/config');
const general_helper = require('../../helpers/general');
const { get_access_token } = require('../../models/api_auth');
let api_token = '';



async function init_all_subprocess(list_views){
  general_helper.showDetails(list_views);
  list_views.forEach(function(current_view){
      start_new_subprocess(current_view);
  });

}


async function start_new_subprocess(current_view){
    
  let tickets = await tymeshift_model.get_tickets_by_view(current_view).catch(general_helper.handleUncaughtExceptions);
  let result_save = await tymeshift_model.save_zendesk_tickets_history(tickets, api_token).catch(general_helper.handleUncaughtExceptions);
  general_helper.showDetails("Subprocess "  + current_view.zendesk_view, result_save);

  setTimeout(function(){
      start_new_subprocess(current_view);
  },  20000);
}


async function get_zendesk_views_pcrc(){

    return tymeshift_model.get_zendesk_views_pcrc(api_token)
                            .catch(general_helper.handleUncaughtExceptions);
}


function refresh_token(){
  setTimeout(async function(){
    api_token = await get_access_token();
    refresh_token()
  },CONFIG.integrations.api.token_refresh_time)  
}

async function run(){
    api_token = await get_access_token();
    let list_views = await get_zendesk_views_pcrc();
    init_all_subprocess(list_views);
    refresh_token();
}


run();



