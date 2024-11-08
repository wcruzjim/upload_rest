const axios = require('axios');
const general_helper = require('../../helpers/general');
const intergrall_presencia_model = require("./models/intergrall_presencia_model");
const teo_rest_communication = require('../../models/api_connection_map');
const { get_access_token } = require('../../models/api_auth');
const { convertEmptyToNull } = require('../../models/api_connection_map');


  var integrations = [];
  var api_token = '';
  var data_agents_list = [];
  var integration_type_id = 4;


  (async function start(){  
    try {
      api_token =  await get_access_token();
      integrations = await teo_rest_communication.get_integration_credential_list(integration_type_id, api_token);
    } catch(error){
      general_helper.showDetails(error);
      return start();
    }
    await get_save_data();
  })();


  async function get_save_data() {
    const time_init = Date.now();

    const intergrall_data = await get_all_info_agents(); 
    const intergrall_data_unique = remove_duplicates(intergrall_data.AgentList, 'login'); 
    const list_login_users = intergrall_data_unique.map(function(connectionData){
      return connectionData.login;
    });
    const list_documents_users =  await intergrall_presencia_model.get_documents_users(list_login_users);
    const intergrall_data_formated = await process_intergrall_data(intergrall_data_unique, list_documents_users);
    
    if (intergrall_data_formated){
      try{
        await intergrall_presencia_model.delete_data_intergrall();  
        await sleep(500);
        await intergrall_presencia_model.validate_and_save_data(intergrall_data_formated);
      } catch(error){
        general_helper.showDetails(error);
        return get_save_data();
      }
    }
        
    setTimeout(async ()=>{
      await get_save_data();
    },300000);

    const time_end = Date.now();
    const time_total_seconds = (time_end - time_init)/1000;
    general_helper.showDetails('Tiempo de integración (segundos): ' + time_total_seconds);

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


  async function process_intergrall_data(intergrall_data, data_documents_users){
    data_agents_list = [];
    intergrall_data.map(user1 => {
        let user_document =  data_documents_users ? data_documents_users.find(user2 => user2.usuario_red == user1.login ) : null
        add_connection_agent(user1, user_document);
    })
    return data_agents_list;
  }


  function add_connection_agent(agent_connection, user_document){
    if(agent_connection.ad_hostname){
      data_agents_list.push(
        {
          usuario_red: convertEmptyToNull(agent_connection.login),
          maquina: convertEmptyToNull(agent_connection.ad_hostname),
          documento: convertEmptyToNull(user_document ? user_document.documento : agent_connection.login),
          fecha_creacion: new Date()
        }
      ); 
    }
  }


  function remove_duplicates(data, login) { 
    const map = new Map(); 
    data.forEach(element => map.set(element[login], element)); 
    return Array.from(map.values()); 
  }


  function sleep(ms) {
    return new Promise((resolve) => setTimeout(resolve, ms));
  }
  

