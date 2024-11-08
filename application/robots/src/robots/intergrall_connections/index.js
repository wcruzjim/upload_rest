const axios = require('axios');
const general_helper = require('../../helpers/general');
const { get_access_token } = require('../../models/api_auth');
const CONFIG = require('../../config/config');
const intergrall_model = require("./models/intergrall_model");
const teo_rest_communication = require('../../models/api_connection_map');
const { convertEmptyToNull } = require('../../models/api_connection_map');


  var last_random_fingerprint = null;
  var integrations = [];
  var api_token = '';
  var data_agents_list = [];
  var integration_type_id= 4;


  (async function start(){  
    try {
      api_token =  await get_access_token();
      integrations = await teo_rest_communication.get_integration_credential_list(integration_type_id, api_token);
    } catch(error){
      general_helper.showDetails(error);
      return start();
    }
    await intergrall_model.delete_data_intergrall(integrations[0].integration_id);
    await get_save_data();
  })();


  async function get_save_data() {
    const time_init = Date.now();
    const intergrall_data = await get_all_info_agents();
    const random_finger_print = Math.floor(general_helper.randomSecure() * 10000000000);
    const intergrall_data_formated = await process_intergrall_data(intergrall_data.AgentList, random_finger_print);

    if (intergrall_data_formated){
      if(last_random_fingerprint){
        await intergrall_model.validate_and_save_data(intergrall_data_formated);
        await teo_rest_communication.delete_data(last_random_fingerprint);
        last_random_fingerprint = random_finger_print;  
      }else{
        await intergrall_model.validate_and_save_data(intergrall_data_formated);
        last_random_fingerprint = random_finger_print;
      }
    }

    setTimeout(async ()=>{
      await get_save_data();
    },integrations[0].tiempo_espera_ciclo);

    const time_end = Date.now();
    const time_total_seconds = (time_end - time_init)/1000;
    general_helper.showDetails('Tiempo de integración: ' + time_total_seconds);

  }


  async function get_all_info_agents() {
    let error_counter = 0;
    let retry = false;
    let agents_status_response = [];
    const api = axios.create({
      baseURL: integrations[0].host,
      timeout: 60000,
      headers: { 'X-IntergrAll-Key' : integrations[0].token_conexion}
    });
  
    do{
      try {
        agents_status_response = await api.get(`/webservices/cosmos_api?action=getAgentsStatus`);
        retry = false;
      }catch(error){
        retry = true;
        error_counter++;
        general_helper.showDetails(error);
        if( error_counter > 5){
          return;
        }
      }
    }while(retry)
    return agents_status_response.data;
  }


  async function process_intergrall_data(intergrall_data, random_finger_print){
    data_agents_list = [];
    intergrall_data.map(agent => {
      process_agent_data(agent, random_finger_print);
    })
    return data_agents_list;
  }


  function process_agent_data(agent_data, random_finger_print){ 
    if (agent_data.skills_possible.length == 0 || agent_data.skills_possible == undefined || agent_data.skills_possible == null){
      let agent_conection = Object.assign (agent_data, {skills_possible : 'intergrall_skill_generic'});
      add_connection_agent(agent_conection, random_finger_print);
      return;
    }
    let agent_skills = agent_data.skills_possible.split(/\s+/);
    agent_skills.forEach(function(skill){
      let agent_conection = Object.assign (agent_data, {skills_possible : skill});
      add_connection_agent(agent_conection, random_finger_print);
    })
  }


 function add_connection_agent(agent_conection, random_finger_print){
    data_agents_list.push(
      {
        first_name: convertEmptyToNull(agent_conection.login),
        id_login: convertEmptyToNull(agent_conection.login),
        extension: agent_conection.ad_hostname ? agent_conection.ad_hostname : null,
        reason: convertEmptyToNull(agent_conection.reason),
        state: convertEmptyToNull(agent_conection.status),
        skill: convertEmptyToNull(agent_conection.skills_possible),
        time: convertEmptyToNull(agent_conection.secs_status),
        priority: 1,
        current_skill: convertEmptyToNull(agent_conection.skill_active ? agent_conection.skill_active: agent_conection.skills_possible),
        finger_print: Number(integrations[0].integration_id),
        platform: 'intergrall',
        unique_finger_print: random_finger_print
      }
    ); 
  }

