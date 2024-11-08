const teo_rest_communication = require('../../models/api_adherence');
const sigma_distribution_model = require("./models/sigma_distribution_model");
const general_helper = require('../../helpers/general');
const helper_logs = require("../../helpers/logs");
const { get_access_token } = require('../../models/api_auth');
const CONFIG = require('../../config/config');
const fingerprint = 4;
let api_token = '';


async function start_process(){
    api_token = await get_access_token();
    const integration_credential_list = await teo_rest_communication.get_integration_credential_list(fingerprint, api_token);
    const formatted_integration_credential_list = sigma_distribution_model.format_integration_credentials(integration_credential_list);
    const genesys_credentials = formatted_integration_credential_list[0];
    try{
        let result_distri_jarvis = await sigma_distribution_model.getDistributionForSigma(api_token);
        general_helper.showDetails( `Rows from Jarvis ${result_distri_jarvis.length}`);
        let result_delete_distri_sigma = await sigma_distribution_model.deleteDistributionSigma(genesys_credentials);
        general_helper.showDetails('Result delete distribution',result_delete_distri_sigma);
        let result_distri_sigma = await sigma_distribution_model.insertDistributionSigma(result_distri_jarvis, genesys_credentials);
        general_helper.showDetails(`Result insert into sigma ${result_distri_sigma}`);
        
        let result_operation = {
            'rows_jarvis_distribution' : result_distri_jarvis.length,
            'result_delete_sigma_distribution' : result_delete_distri_sigma,
            'result_insert_sigma_distribution' : result_distri_sigma
        };

        helper_logs.create_log(10, result_operation , 'debug', 'worker_states');
        return result_operation;
    }
    catch(err){
        general_helper.showDetails(`Error operation process copy distribution from jarvis to sigma ${err}`);
        return err;
    }
}

module.exports = {start_process}