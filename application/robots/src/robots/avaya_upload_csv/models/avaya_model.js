const fs = require("fs");
const teo_rest_communication = require('../../../models/api_connection_map');
const config_bot = require("../config/config");
const general_helper = require('../../../helpers/general');

async function read_file_path(random_fingerprint, fingerprint) {
  return new Promise(async (resolve) => {
    config_bot.routes.forEach(async function (route) {
      const data_formated = await check_file_exists(route, random_fingerprint, fingerprint)
      return resolve(data_formated);
    });
  });
}

async function check_file_exists(current_file, random_fingerprint, fingerprint) {
  return new Promise(async (resolve, reject) => {
    fs.stat(current_file.path, async function (err) {
      if (err) {
        general_helper.showDetails("Error trying to read csv file [" + current_file.path + "]", err);
        return reject(err);
      }
      const parsed_data = await parse_file(current_file);
      const data_no_header = parsed_data.slice(1, parsed_data.length - 1);
      const final_data = data_no_header.map(row => {
        const splitted_data = row.split("\t");
        return {
          first_name: splitted_data[2],
          id_login: splitted_data[3],
          extension: splitted_data[4],
          reason: splitted_data[5],
          state: splitted_data[6],
          skill: `${fingerprint}_${splitted_data[0]}`,
          time: splitted_data[9],
          priority: splitted_data[11],
          current_skill: `${fingerprint}_${splitted_data[8]}`,
          finger_print: fingerprint,
          platform: current_file.platform,
          unique_finger_print: random_fingerprint
        }
      });
      resolve(final_data)
    })
  });
}

async function parse_file(current_file) {
  return new Promise((resolve, reject) => {
    fs.readFile(current_file.path, 'latin1', function (err, data) {
      const data_array = data.split("\r\n");
      if (err) {
        return reject(err);
      }
      resolve(data_array)
    })
  })
}

async function get_connected_status(random_fingerprint, fingerprint) {
  const data = await read_file_path(random_fingerprint, fingerprint);
  return new Promise((resolve) => {
    resolve(data)
  })
}

async function send_chunks_inserts(data) {
  
  let validation = teo_rest_communication.connection_map_data_validation(data);
  if(!validation){
    throw new Error('Error trying to save connections');
  }


  let array_promises = [];
  const chunk_size = 1000;
  for (let index = 0; index < data.length; index = index + chunk_size) {
    const chunk = data.slice(index, index + chunk_size);
    array_promises.push(teo_rest_communication.save_chunk(chunk));
  }
  const results_all_promises = await Promise.all(array_promises);
  return results_all_promises;
}

module.exports = {
  get_connected_status,
  send_chunks_inserts
}
