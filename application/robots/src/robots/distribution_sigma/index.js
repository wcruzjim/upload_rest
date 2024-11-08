const path = require("path");
const COSMOS_CONFIG = require('../../config/config');
const Distribution_model = require("../../models/distribution");
const Sigma_distribution_model = require("../../models/sigma_distribution");
const helper_logs = require("../../helpers/logs.js");
const general_helper = require('../../helpers/general');


async function start_process(){
    try{
        let result_distri_jarvis = await Distribution_model.getDistributionForSigma();
        general_helper.showDetails( `Rows from Jarvis ${result_distri_jarvis.length}`);
        let result_delete_distri_sigma = await Sigma_distribution_model.deleteDistributionSigma();
        general_helper.showDetails('Result delete distribution',result_delete_distri_sigma);
        let result_distri_sigma = await Sigma_distribution_model.insertDistributionSigma(result_distri_jarvis);
        general_helper.showDetails( `Result insert into sigma ${result_distri_sigma}`);
        
        let result_operation = {
            'rows_jarvis_distribution' : result_distri_jarvis.length,
            'result_delete_sigma_distribution' : result_delete_distri_sigma,
            'result_insert_sigma_distribution' : result_distri_sigma
        };

        helper_logs.create_log(10, result_operation ,'debug', 'worker_states');
        return result_operation;
    }
    catch(err){
        general_helper.showDetails("Error operation process copy distribution from jarvis to sigma", err);
        return err;
    }
}


function delay_start_process(){
    setTimeout(start_process, 10000)
}

module.exports = {start_process}