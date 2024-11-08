const { get_access_token } = require('../../models/api_auth');
const CONFIG = require('../../config/config');
const avaya_model = require("./models/avaya_model");
const teo_rest_communication = require('../../models/api_connection_map');
const general_helper = require('../../helpers/general');
// Es seguro porque se ejecuta por un admin dentro del servidor, no se expone
const argv = require('yargs/yargs')(process.argv.slice(2))
.example(`--integration_type_id={xxx}`)
.describe('integration_type_id', 'Integration type ID')
.demandOption(['integration_type_id'])
.argv
const fingerprint = argv.integration_type_id;
var last_random_fingerprint = null;
var api_token = "";

async function update_repit_data() {
  let time_initial = Date.now();
  console.time('total_time');
  const random_fingerprint = Math.floor(general_helper.randomSecure() * 10000000000);
  await get_save_data(random_fingerprint, fingerprint);
  if (last_random_fingerprint) {
    console.time('delete_data');
    await delete_data(last_random_fingerprint);
    console.timeEnd('delete_data');
  }
  last_random_fingerprint = random_fingerprint
  console.timeEnd('total_time');
  let time_final = Date.now();
  let seconds = (time_final - time_initial)/1000;
  let seconds_delay = seconds < 2 ? 2000 : 0;
  setTimeout(update_repit_data , seconds_delay);
}

async function delete_data(last_random_fingerprint) {
  await teo_rest_communication.delete_data(last_random_fingerprint);
}

async function get_save_data(random_fingerprint, fingerprint) {
  console.time('get_data');
  const avaya_data = await avaya_model.get_connected_status(random_fingerprint, fingerprint);
  console.timeEnd('get_data');
  console.time('save_data');
  await avaya_model.send_chunks_inserts(avaya_data);
  console.timeEnd('save_data');
}

async function run(){
  api_token =  await get_access_token();
  update_repit_data();
  refresh_token();
}

function refresh_token(){
  setTimeout(async function(){
    api_token = await get_access_token();
    refresh_token()
  },CONFIG.integrations.api.token_refresh_time)  
}

run();
