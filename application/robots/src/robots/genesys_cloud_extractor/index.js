const axios = require('axios');
const CONFIG =  require('./config/config');
const { get_access_token } = require('../../models/api_auth');
const teo_rest_communication = require('../../models/api_connection_map');
const model = require('./model');
const general_helper = require('../../helpers/general');
const qs = require('qs');
const http = require('http');
const https = require('https');

process.env.TZ = CONFIG.generalData[0].tz;

var dataUsers = {};
var integrations = [];
var api_token = "";
var dictionary_external_extensions = [];

const httpAgent = new http.Agent({ keepAlive: true });
const httpsAgent = new https.Agent({ keepAlive: true });

const api = axios.create({
    baseURL: CONFIG.generalData[0].urlApiGeneysCloud,
    timeout: CONFIG.generalData[0].timeOut,
    httpAgent,
    httpsAgent,
    maxContentLength: 50 * 1024 * 1024, 
    maxBodyLength: 50 * 1024 * 1024
  });

async function accessTokenCloud(client_id, client_secret){
    const authUrl = CONFIG.generalData[0].urlOatuthCloud;
    const data = qs.stringify({
        'grant_type': 'client_credentials'
      });
    
      const auth = {
        username: client_id,
        password: client_secret
      };
    
      const config = {
        method: 'post',
        url: authUrl,
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        auth: auth,
        data: data
      };

    const response = await axios(config);
    const accessToken = response.data.access_token;
    return accessToken;
}

function refresToken(){
    setInterval(async () => {
        api_token =  await get_access_token();
    }, CONFIG.generalData[0].timeRefreshToken); 
}

function extensionFormat(str){
    // temporal disable extension format to allow string
    if(str){
        return str;
    }
    let hash = 0;
    str = str ? str : "";

    if(str.length == 0){
        return hash;
    }

    for (let i = 0; i < str.length; i++){
        let char = str.charCodeAt(i);
        hash = ((hash << 5) -  hash) + char;
        hash = hash & hash; 
    }

    return Math.abs(hash) % 1000000000

}
   
async function getAllQueues(id) {

    const headers = { Authorization: `Bearer ${dataUsers[id].token}` };
    let errorCounter = 0;
    let retry = false;
    let queuesResponse = [];

    do{
        try {
            dataUsers[id].apiCounter++;
            queuesResponse = await api.get(`/routing/queues?pageSize=200&pageNumber=1`, {  headers });
            retry = false;
        }catch(error){
            retry = true;
            errorCounter++;
            general_helper.showDetails(error);
            if( errorCounter > 5){
                return;
            }
        }
    }while(retry)
    const queuesData = queuesResponse.data.entities.map(queue => ({ queueId: queue.id, name: queue.name }));
    dataUsers[id].queues = queuesData;
}


async function delay(ms) {
     ms =  ms * 2000;
     return new Promise(resolve => setTimeout(resolve, ms));
}

function formatDate(date){

    const dateRegister = new Date(date);
    
    const actualDate =  new Date();

    const difference = actualDate - dateRegister;

    const time = Math.floor(difference / 1000);

    return time;
    
}
 
  (async function start(){  
    try{
        api_token =  await get_access_token();
        await  getExternalExtensions();
        integrations = await teo_rest_communication.get_integration_credential_list(2, api_token);
    }catch(error){
        general_helper.showDetails(error);
        return start();
    }
    refresToken();
    await updateData();
  })();



  async function getPresenceDefinitions(params) {

    const headers = { Authorization: `Bearer ${dataUsers[params.id].token}`};
    try{

        let response = await api.get(`/presencedefinitions?pageSize=500`, { headers });
        dataUsers[params.id].presenceDefinitions = response?.data?.entities || [];
        dataUsers[params.id].presenceDefinitions = formatPresenceDefinitionList(dataUsers[params.id].presenceDefinitions, params.lang_presencia );
    }
    catch(err){
        general_helper.showDetails(`error getting presence list`);
        general_helper.showDetails(err);
    }
}



  function compararFechas(fecha_inicio, fecha_fin){

        let currentDate = new Date();
        let confirmation = false;

        let timeStart = fecha_inicio
        let timeEnd = fecha_fin
        let [hour_start, minutes_start, seconds_start] = timeStart.split(':');
        let [hour_end, minutes_end, seconds_end] = timeEnd.split(':');

        let dateStart = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate(), hour_start, minutes_start, seconds_start);
        let dateEnd = new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate(), hour_end, minutes_end, seconds_end);

        if(currentDate >= dateStart && currentDate <= dateEnd){

            confirmation = true;

        } 

        return confirmation

  }

  async function refreshTokenCloud(params){

    if(params.fecha_inicio && params.fecha_fin){

        let confirmation = compararFechas(params.fecha_inicio, params.fecha_fin);

            if(confirmation){  

                let counter = 0;
                let retry = false;
            
                do{
                    try{

                        dataUsers[params.id].apiCounter++;
                        let accessToken = await accessTokenCloud(params.user, params.password);
                        dataUsers[params.id].token = accessToken;
                        retry = false;
                        counter = 0;
                    }catch(error){
                        retry = true;
                        counter++;
                        general_helper.showDetails(error)
                        if(counter > 5){
                            dataUsers[params.id].token = undefined;
                            return;
                        }
                    }
                }while(retry);
            
                setTimeout(async ()=>{
                    await refreshTokenCloud(params);
                }, 360000); 

            }
        } else {
            general_helper.showDetails("La hora actual no se encuentra en los tiempos establecidos para la libre ejecución del bot");
        }
   
  }

  function proccesDataAgent(params){
       
            let user_skills = params.user.skills;

            if(user_skills == undefined || user_skills == null || user_skills.constructor !== Array || user_skills.length <  1){
                addConnectionUserRow(params);
                return;
            }
            params.skill = true;

            // Create a  row connection for each skill of user
            user_skills.forEach(function(skill){

                let userConnection = Object.assign({
                                                                                    skill: true,
                                                                                    userQueue : { 
                                                                                        name : skill.name
                                                                                     }
                                                                                }, params);

                addConnectionUserRow( userConnection );
            })

       
  }

 
  function addConnectionUserRow(params){
    dataUsers[params.id].usersData.push(
        {   
            skill: params.skill ? `${params.id}_${params.userQueue.name}` :  params.database +  '_cola_generica_cloud',
            first_name: params.userName === undefined || params.userName === null ? params.user.id : params.userName,
            id_login: params.user.id,
            extension: params.formatExtension,
            reason: params.reason,
            state: params.user.presence.presenceDefinition.systemPresence,
            current_skill: params.skill ? `${params.id}_${params.userQueue.name}` :   params.database +  '_cola_generica_cloud',
            time: params.time,
            finger_print: params.database,
            platform: CONFIG.generalData[0].dataBase,
            unique_finger_print: params.uniqueFingerPrint
        }
    )
  }
  

  function getSecondaryUserStatus(user, integration){
    // if user presence is one of the following list. then use routing status instead secondary presence
    // Example if primary presence is On Queue, the secondary status will be IDLE or INTERACTING
    let list_singular_routing_status = ["On Queue"];
    let presenceDefinitionUserID = user?.presence?.presenceDefinition?.id;
    let presenceDefinitionSystemUser = user?.presence?.presenceDefinition?.systemPresence;
    let prsenceDefinitionRoutingUser = user?.routingStatus?.status;
    let presenceDefinition = dataUsers[integration.id].presenceDefinitions[  presenceDefinitionUserID ];

    if( list_singular_routing_status.includes( presenceDefinitionSystemUser ) ){
        return prsenceDefinitionRoutingUser;
    }
    
    // if definition does not exist or definition is primary, then secondary presence will be null
    if( !presenceDefinition ||  presenceDefinition.primary ){
        return null;
    }
    

    return presenceDefinition.definition;
  }

  async function getDataUserInterval(paramsIntegration){

    if(paramsIntegration.fecha_inicio && paramsIntegration.fecha_fin){

        let confirmation = compararFechas(paramsIntegration.fecha_inicio, paramsIntegration.fecha_fin);

        if(confirmation){  

            console.time(`Tiempo integración ${paramsIntegration.id}`);
            if(!dataUsers[paramsIntegration.id].token){
                general_helper.showDetails('Error consolidando data de asesor, token invalido')
                return;
            }


            let errorCounter = 0
            let retry = false;
            let basicData = [];
            const requireTranformation = paramsIntegration.requiere_transformacion;
            const tranformationType = paramsIntegration.tipo_transformacion;

            do{
                try{
                    dataUsers[paramsIntegration.id].usersData = [];
                    const headers = { Authorization: `Bearer ${dataUsers[paramsIntegration.id].token}`};
                    let uniqueFingerPrint = Math.floor(general_helper.randomSecure());
                    dataUsers[paramsIntegration.id].apiCounter++;
                    let userData = await api.get(`/users?expand=skills,presence,station,routingStatus&pageNumber=1&pageSize=500`, { headers });
                    let pages = userData.data.pageCount;

                    if(pages == 1){
                        let datos = userData.data.entities;
                        await Promise.all(
                            datos.map(async user => {
                                if(user.presence.presenceDefinition.systemPresence != "Offline"){
                                    const formatExtension = extensionFormat(user?.station?.effectiveStation?.id);
                                    const time = formatDate(user.presence.modifiedDate);
                                    let userNameMap = user[paramsIntegration.mapeo_usuario];
                                    
                                    if(requireTranformation == 1){
                                        userNameMap = transformations(userNameMap, tranformationType);
                                    }

                                    
                                    let params = {
                                        user: user,
                                        userName: userNameMap,
                                        time: time,
                                        formatExtension: formatExtension,
                                        id: paramsIntegration.id,
                                        database: paramsIntegration.id,
                                        uniqueFingerPrint: uniqueFingerPrint,
                                        skill: false,
                                        reason: getSecondaryUserStatus( user, paramsIntegration )
                                    }

                                    proccesDataAgent(params);

    
                                    basicData.push(
                                        {   
                                            id_int_integraciones: paramsIntegration.id,
                                            usuario: userNameMap,
                                            documento: user[paramsIntegration.mapeo_documento],
                                            codigo_logueo: user.id,
                                            json_data_agente: JSON.stringify(user)
                                        }
                                    )
                                }
                            })
                        );
                
                    }else{
                        const userPromises = [];
                        for(let i = 1; i <= pages; i++){
                            let retries = 0;

                            const retryFetch = async () => {
                                try {
                                    await delay(Math.random() * 3);
                                    dataUsers[paramsIntegration.id].apiCounter++;
                                    const response = await api.get(`/users?expand=skills,presence,station,routingStatus&pageNumber=${i}&pageSize=500`, { headers })
                                    return response;
                                } catch (error) {
                                    retries++;
                                    if (retries <= 5) {
                                        general_helper.showDetails(`Reintentando (${retries} de 5)... Reintendando la página ${i} del id: ${paramsIntegration.id}`);
                                        await delay(Math.random() * 3); 
                                        return retryFetch();
                                    } else {
                                        general_helper.showDetails(`No se pudo obtener la respuesta de los miembros a pesar de 5 intentos.`);
                                        throw error; 
                                    }
                                }
                            };
                            userPromises.push(retryFetch());
                        }
                        const usersResponses = await Promise.all(userPromises);
                        await Promise.all(
                            usersResponses.map(async (userResponse) => {
                                await Promise.all(
                                    userResponse.data.entities.map(async user_pages => {
                                        const formatExtension = extensionFormat(user_pages?.station?.effectiveStation?.id);
                                        
                                        if(user_pages.presence == undefined){
                                            console.log("presencia erronea",user_pages.name);
                                            return;
                                        }

                                        const time = formatDate(user_pages.presence.modifiedDate);
                                        
                                        let userNameMap = user_pages[paramsIntegration.mapeo_usuario];
                                        
                                        if(requireTranformation == 1){
                                            userNameMap = transformations(userNameMap, tranformationType);
                                        }

                                        if(user_pages.presence.presenceDefinition.systemPresence != "Offline"){
                                            
                                            let params = {
                                                user: user_pages,
                                                userName: userNameMap,
                                                time: time,
                                                formatExtension: formatExtension,
                                                id: paramsIntegration.id,
                                                database: paramsIntegration.id,
                                                uniqueFingerPrint: uniqueFingerPrint,
                                                skill: false,
                                                reason: getSecondaryUserStatus( user_pages, paramsIntegration )

                                            }

                                            proccesDataAgent(params);
    
                                            basicData.push(
                                                {   
                                                    id_int_integraciones: paramsIntegration.id,
                                                    usuario: userNameMap,
                                                    documento: user_pages[paramsIntegration.mapeo_documento],
                                                    codigo_logueo: user_pages.id,
                                                    json_data_agente: JSON.stringify(user_pages)
                                                }
                                            )
                                        }            
                                    })
                                )
                            })
                        ) 
                    }


                    if(dataUsers[paramsIntegration.id].usersData){
                        
                        let validacion = teo_rest_communication.connection_map_data_validation(dataUsers[paramsIntegration.id].usersData);
            
                        if(!validacion){
                            return;
                        }
                    }


                    // use external map extension from jarvis client
                    if(paramsIntegration.extension_externa){
                        dataUsers[paramsIntegration.id].usersData = linkExternalExtensionToUser( dataUsers[paramsIntegration.id].usersData );
                    }

                    if(dataUsers[paramsIntegration.id].fingerPrint){
                        await model.send_chunks_inserts(dataUsers[paramsIntegration.id].usersData);
                        await teo_rest_communication.delete_data(dataUsers[paramsIntegration.id].fingerPrint);
                        dataUsers[paramsIntegration.id].fingerPrint = uniqueFingerPrint;  
                    }else{
                        await model.send_chunks_inserts(dataUsers[paramsIntegration.id].usersData);
                        dataUsers[paramsIntegration.id].fingerPrint = uniqueFingerPrint;
                    }
                    
                    dataUsers.usersBasicData[paramsIntegration.id] = basicData;

                    dataUsers[paramsIntegration.id].usersData = null;
                 
                    retry = false;
                    errorCounter = 0
                
                }catch(error){
                    general_helper.showDetails( error )
                    retry = true;
                    errorCounter++;
                    if(errorCounter > 5){
                        general_helper.showDetails('Errores seguidos procesando data de agentes, se procede a suspender integración');
                        general_helper.showDetails(error);
                        return;
                    }
                    general_helper.showDetails(`Error en users para la integración: ${paramsIntegration.id}`);
                }
            }while(retry)

            setTimeout(async ()=>{
                await getDataUserInterval(paramsIntegration);
            }, paramsIntegration.tiempo_espera_ciclo);

            console.timeEnd(`Tiempo integración ${paramsIntegration.id}`);
        } else {
            general_helper.showDetails("La hora actual no se encuentra en los tiempos establecidos para la libre ejecución del bot");
        }

    }

  }

  function linkExternalExtensionToUser(users){

    users = users.map(function(user){
        if(dictionary_external_extensions[ user.first_name ]){
            user.extension = dictionary_external_extensions[ user.first_name ]
            return user;
        }
        if(dictionary_external_extensions[ user.id_login ]){
            user.extension = dictionary_external_extensions[ user.id_login ]
            return user;
        }

        return user;
    })

    return users;
  }

  async function refreshTokenCloudDataBase(params, token){   
   
    if(!token){
        token = await teo_rest_communication.get_integration_api_token(params.id, api_token);
        token = token[0].token_conexion;
    }

    dataUsers[params.id].token = token;

    setTimeout(async ()=>{
        await refreshTokenCloudDataBase(params);
    }, 360000); 
    
  }

  function transformations(dataTransform, idTransform){

        if(idTransform == 1){
            dataTransform = dataTransform.split("-")[0].trim();
        }

        return dataTransform;
    }

  function showApiusage(id){

    general_helper.showDetails(`Consumo por minuto de la integración con id: ${id} es igual a: ${dataUsers[id].apiCounter}`);
    dataUsers[id].apiCounter = 0;
  }     

  async function insertBasicDataUser(){
        let dataMerged = [];
        await teo_rest_communication.delete_basic_data_user();
        dataMerged = [].concat(...dataUsers.usersBasicData);
        dataMerged = dataMerged.filter(elemento => elemento !== undefined && elemento.length !== 0);
        await model.send_chunks_basic_data_user(dataMerged);

        setTimeout(async ()=>{
            await insertBasicDataUser();
        }, 1800000);
  }

  async function updateData() {
    await Promise.all(
      integrations.map(async (integration) => {

            let confirmation_fecha = false;
            let params = {};
    
            if (integration.fecha_inicio_proceso && integration.fecha_fin_proceso) {
            confirmation_fecha = model.isValidDate([
                integration.fecha_inicio_proceso,
                integration.fecha_fin_proceso
            ]);
            }
    
            if (confirmation_fecha) {
            params = {
                fecha_inicio: integration.fecha_inicio_proceso,
                fecha_fin: integration.fecha_fin_proceso
            };
            }
    
            params.id = Number(integration.integration_id);
            params.user = integration.user;
            params.password = integration.password;
            params.tiempo_espera_ciclo = integration.tiempo_espera_ciclo;
            params.mapeo_usuario = integration.campo_mapeo_usuario;
            params.mapeo_documento = integration.campo_mapeo_documento;
            params.requiere_transformacion = integration.requiere_transformacion;
            params.tipo_transformacion = integration.tipo_transformacion;
            params.extension_externa = integration.extension_externa === '1' ? true : false;
    
            Object.assign(dataUsers, {
            [integration.integration_id]: { usersData: [], apiCounter: 0 },
            usersBasicData: [[integration.integration_id] = []] }
            );
    
            if (integration.token_conexion) {
            await refreshTokenCloudDataBase(params, integration.token_conexion);
            } else {
            await refreshTokenCloud(params);
            }

            await getPresenceDefinitions(params);
            await getDataUserInterval(params);
    
            setInterval(showApiusage, 60000, params.id);   
      })
    );
    
    await insertBasicDataUser();

  }

  function mapDictionaryExternalExtensions(externalExtensions){
    let dictionaryExtensions = {}

    externalExtensions.forEach((localUser)=> {
        dictionaryExtensions[ localUser['usuario'] ] = localUser['maquina'];
    });

    return dictionaryExtensions;
  }

  async function getExternalExtensions(){
    let local_dictionary_external_extensions = await teo_rest_communication.getExternalExtensionsByPlatformID(28, api_token)
    local_dictionary_external_extensions = mapDictionaryExternalExtensions(local_dictionary_external_extensions);
    dictionary_external_extensions = local_dictionary_external_extensions;
    
    setTimeout( getExternalExtensions , 60000);
    return true
  }



  
function formatPresenceDefinitionList(presenceList, lang){

    let hasMapList = {};

    if(presenceList === undefined || presenceList == null || presenceList.constructor !== Array){
        return hasMapList;
    }

    presenceList.forEach(function(presenceItem){
        hasMapList[presenceItem.id] =   getPresenceDefinitionByLang( presenceItem, lang ) 
    })

    return hasMapList;
}

function getPresenceDefinitionByLang(presence, lang){

    let availableLanguages = Object.keys( presence.languageLabels );

    if(availableLanguages.length < 1){
        return null;
    }

    // If lang is not defined. try to use  the first one of the list
    if(!lang && availableLanguages.length > 0){
        lang = availableLanguages[0];
    }

    if(!lang){
        return null;
    }

    // if presence exists in specific language, choose it, otherwise, choose the first one by default
    if( presence.languageLabels[lang] ){
        return { 
                            id: presence.id,
                            systemPresence: presence.systemPresence,
                            definition : presence.languageLabels[lang],
                            primary : presence.primary
                    };
    } 

    // default lang used
    return  presence.languageLabels[ availableLanguages[0] ];
}


