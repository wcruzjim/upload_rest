const genesys_model = require("./models/genesys_model");
const CONFIG = require('../../config/config');
const { get_access_token } = require('../../models/api_auth');
const teo_rest_communication = require('../../models/api_connection_map');
const general_helper = require('../../helpers/general');
// Es seguro porque se ejecuta por un admin dentro del servidor, no se expone
const argv = require('yargs/yargs')(process.argv.slice(2))
.example(`--integration_type_id={xxx}`)
.describe('integration_type_id', 'Integration type ID')
.demandOption(['integration_type_id'])
.argv
const integration_type_id = argv.integration_type_id;
var last_random_fingerprints = [];
var api_token = "";

function get_status_list() {
  return {
    0: 'Deslogueado',
    1: 'Logueado',
    2: 'No listo',
    3: 'Disponible',
    4: 'ACW',
    5: 'En llamada',
    6: 'Desconocido'
  }
}


async function update_repit_data(genesys_credentials) {
  let time_initial = Date.now();
  const fingerprint = genesys_credentials.integration_id;
  const database = genesys_credentials.database;
  console.time(`${fingerprint}_${database}_total_time`);
  const random_fingerprint = Math.floor(general_helper.randomSecure() * 10000000000);
  await get_save_data(random_fingerprint, genesys_credentials);
  const last_random_fingerprint = last_random_fingerprints.find(integration => integration.integration_id == genesys_credentials.integration_id).random_fingerprint;
  if (last_random_fingerprint) {
    console.time(`${fingerprint}_${database}_delete_data`);
    await delete_data(last_random_fingerprint);
    console.timeEnd(`${fingerprint}_${database}_delete_data`);
  }
  last_random_fingerprints.find(integration => integration.integration_id == genesys_credentials.integration_id).random_fingerprint = random_fingerprint;
  console.timeEnd(`${fingerprint}_${database}_total_time`);
  let time_final = Date.now();
  let seconds = (time_final - time_initial)/1000;
  let seconds_delay = seconds < 2 ? 2000 : 0;
  setTimeout(function(){
    update_repit_data(genesys_credentials)
  }, seconds_delay);
}


async function delete_data(last_random_fingerprint) {
  await teo_rest_communication.delete_data(last_random_fingerprint);
}

async function get_save_data(random_fingerprint, genesys_credentials) {
  const fingerprint = genesys_credentials.integration_id;
  const database = genesys_credentials.database;
  const statusList = get_status_list();
  console.time(`${fingerprint}_${database}_get_data`);
  const data_genesys = await genesys_model.get_connected_status(genesys_credentials);
  console.timeEnd(`${fingerprint}_${database}_get_data`);
  console.time(`${fingerprint}_${database}_format data`);
  const formattedData = genesys_model.format_connection_result(data_genesys, fingerprint, random_fingerprint, statusList);
  console.timeEnd(`${fingerprint}_${database}_format data`);
  console.time(`${fingerprint}_${database}_save_data`);
  await genesys_model.send_chunks_inserts(formattedData);
  console.timeEnd(`${fingerprint}_${database}_save_data`);
}

async function run(){
  api_token =  await get_access_token();
  const integration_credential_list = await teo_rest_communication.get_integration_credential_list(integration_type_id, api_token);
  last_random_fingerprints = genesys_model.format_random_fingerprint(integration_credential_list);
  const formatted_integration_credential_list = genesys_model.format_integration_credentials(integration_credential_list);
  formatted_integration_credential_list.forEach(genesys_credentials => {
    update_repit_data(genesys_credentials);
  });
  refresh_token();
}

function refresh_token(){
  setTimeout(async function(){
    api_token = await get_access_token();
    refresh_token()
  },CONFIG.integrations.api.token_refresh_time)  
}

run();
