const CONFIG = require('../config/config');
const axios = require('axios');
const general_helper = require('../helpers/general');
const {_db} = require('../app/databases');


async function save_chunk(chunk) {

  return new Promise(function(resolve, reject){  
    _db.getConnection("cosmos",function(err,connection){
      if(err){
        general_helper.showDetails(`Error trying to connect to Cosmos DB. ${err}`);
        reject(err);
      }
    
      if(connection){
        chunk = chunk.map(function(connectionData){
          return [
            convertEmptyToNull(connectionData.skill),
            convertEmptyToNull(connectionData.first_name),
            convertEmptyToNull(connectionData.id_login),
            convertEmptyToNull(connectionData.extension),
            convertEmptyToNull(connectionData.reason),
            convertEmptyToNull(connectionData.state),
            convertEmptyToNull(connectionData.current_skill),
            convertEmptyToNull(connectionData.time),
            convertEmptyToNull(connectionData.priority),
            convertEmptyToNull(connectionData.finger_print),
            convertEmptyToNull(connectionData.platform),
            convertEmptyToNull(connectionData.unique_finger_print)
          ];
        });

        connection.query(`INSERT INTO connections_map
                        ( skill,
                          first_name,
                          id_login,
                          extension,
                          reason,
                          state,
                          current_skill,
                          time,
                          priority,
                          finger_print,
                          platform,
                          unique_finger_print)
                      VALUES ?`,
                      [chunk],
                      function(err){
                        connection.release();
                        return (err) ? reject(new Error(err)) : resolve(true);
        });
      }
    });
  });
}

function convertEmptyToNull(value) {
  if (
    value === null ||
    value === undefined ||
    (typeof value === "string" && value.trim() === "")
  ) {
    return null;
  }
  return typeof value === "string" ? value.trim() : value;
}

async function save_chunk_basic_data_user(chunk) {

  return new Promise(function(resolve, reject){  
    _db.getConnection("jarvis",function(err,connection){
      if(err){
        general_helper.showDetails(`Error trying to connect to Cosmos DB. ${err}`);
        reject(err);
      }
    
      if(connection){
        chunk = chunk.map(function(connectionData){
          return [
            connectionData.id_int_integraciones,
            connectionData.usuario == null ? connectionData.usuario : connectionData.usuario.trim(),
            connectionData.documento == null ? connectionData.documento : connectionData.documento.trim(),
            connectionData.codigo_logueo == null ? connectionData.codigo_logueo : connectionData.codigo_logueo.trim(),
            connectionData.json_data_agente == null ? connectionData.json_data_agente.replace(/[^\x00-\x7F]/g, '') : connectionData.json_data_agente.replace(/[^\x00-\x7F]/g, '').trim()
          ];
        });
        connection.query(`INSERT INTO int_usuarios_integraciones
                        ( id_int_integraciones,
                          usuario,
                          documento,
                          codigo_logueo,
                          json_data_agente)
                      VALUES ?`,
                      [chunk],
                      function(err){
                        connection.release();
                        return (err) ? reject(new Error(err)) : resolve(true);
        });
      }
    });
  });
}


function connection_map_data_validation(data) {

  const stateRegex = /^[A-Za-z ]+$/;

  let isValid = true;

  for (const connectionData of data) {
    if (connectionData.skill === undefined || connectionData.skill === null || connectionData.skill.length < 1  ) {
      general_helper.showDetails(`El valor ${connectionData.skill} no es válido para el campo Skill.  debe de contener al menos 1 caracter`);
      isValid = false;
    }

    if (  connectionData.first_name === undefined || connectionData.first_name === null || connectionData.first_name.length < 1 ) {
      general_helper.showDetails(`El valor ${connectionData.first_name} no es válido para el campo First name. Es requerido y solo debe contener letras y el símbolo '.'`);
      isValid = false;
    }

    if (!(stateRegex.test(connectionData.state)) || connectionData.state === null || connectionData.state === undefined) {
      general_helper.showDetails(`El valor ${connectionData.state} no es válido para el campo State. Es requerido y solo debe contener letras y espacios`);
      isValid = false;
    }

    if (typeof connectionData.time !== 'number' || connectionData.time === null || connectionData.time === undefined) {
      general_helper.showDetails(`El valor ${connectionData.time} no es válido para el campo Time. Es requerido y debe ser numérico`);
      isValid = false;
    }

    if (typeof connectionData.finger_print !== 'number' || connectionData.finger_print === null || connectionData.finger_print === undefined) {
      general_helper.showDetails(`El valor ${connectionData.finger_print} no es válido para el campo Fingerprint. Es requerido y debe ser numérico`);
      isValid = false;
    }

    if (typeof connectionData.unique_finger_print !== 'number' || connectionData.unique_finger_print === null || connectionData.unique_finger_print === undefined) {
      general_helper.showDetails(`El valor ${connectionData.unique_finger_print} no es válido para el campo Unique fingerprint. Es requerido y debe ser numérico`);
      isValid = false;
    }
  }

  return isValid;
}




async function delete_data(last_random_fingerprint) {

  return new Promise(function(resolve, reject){  
    _db.getConnection("cosmos",function(err,connection){
      if(err){
        general_helper.showDetails(`Error trying to connect to Cosmos DB. ${err}`);
        reject(err);
      }
      if(connection){


        connection.query(`DELETE FROM connections_map
                          WHERE unique_finger_print = ?`,
                        last_random_fingerprint,
                        function(err){
                          connection.release();
                          return (err) ? reject(new Error(err)) : resolve(true);
        });
      }
    });
  });
}

async function delete_basic_data_user() {
  return new Promise(function(resolve, reject){  
    _db.getConnection("jarvis",function(err,connection){
      if(err){
        general_helper.showDetails(`Error trying to connect to jarvis DB. ${err}`);
        reject(err);
      }
      if(connection){
        connection.query(`DELETE FROM int_usuarios_integraciones`,
                        function(err){
                          connection.release();
                          return (err) ? reject(new Error(err)) : resolve(true);
        });
      }
    });
  });
}



async function get_integration_credential_list(integration_type_id, api_token) {
  if (integration_type_id.length < 1) {
    Promise.resolve(true);
  }

  return new Promise(function(resolve,reject){
    axios.post(`${CONFIG.integrations.api.url}/Gtr/loadIntegrationCredentials`, {
      data:{
        integrationTypeId: integration_type_id
      }
    }, {
      headers: {
        Authorization: api_token
      },
      httpsAgent: new (require('https')).Agent({ rejectUnauthorized: false })
    })
    .then(response => {
      if (response.data == undefined || response.data.result == undefined){
        general_helper.showDetails(response);
        return reject(new Error('Error trying to get integration credentials'));
      }
      resolve(response.data.result);
    })
    .catch((error) => {
      general_helper.showDetails(error);
      reject(new Error('Error trying to get integration credentials'));
    });
  })
 
}

async function get_integration_api_token(id, api_token) {
  if (id < 1) {
    Promise.resolve(true);
  }

  return new Promise(function(resolve,reject){
    axios.post(`${CONFIG.integrations.api.url}/Gtr/getTokenCloudDatabase`, {
      id: id
    }, {
      headers: {
        Authorization: api_token
      },
      httpsAgent: new (require('https')).Agent({ rejectUnauthorized: false })
    })
    .then(response => {
      if (response.data == undefined || response.data.result == undefined){
        return reject(new Error('Error trying to get acces Token'));
      }
      resolve(response.data.result.result_array);
    })
    .catch((error) => {
      general_helper.showDetails(error);
      reject(new Error('to get acces Token'));
    });
  })
 
}



async function getExternalExtensionsByPlatformID(id_platform, api_token) {

  return new Promise(function(resolve,reject){
    axios.post(`${CONFIG.integrations.api.url}/Gtr/getExternalExtensionByPlatformUser`, {
      id_platform: id_platform
    }, {
      headers: {
        Authorization: api_token
      },
      httpsAgent: new (require('https')).Agent({ rejectUnauthorized: false })
    })
    .then(response => {
      if (response.data == undefined || 
          response.data.result == undefined){
        general_helper.showDetails(response);
        return reject(new Error('Error trying to get extensions by pltatform'));
      }
      resolve(response.data.result);
    })
    .catch((error) => {
      general_helper.showDetails(error);
      reject(new Error('Error trying to get extensions by platform'));
    });
  })
 
}

function getUniqueFloors() {
  return new Promise(function (resolve, reject) {
    _db.getConnection("cosmos", function (err, conn) {
      if (err) {
        return reject(err);
      }

      if (conn) {
        conn.query(
          `SELECT DISTINCT floors_map_id FROM floors_map`,
          function (error, results) {
            conn.release();
            if (error) {
              return reject(new Error(error));
            } else {
              const uniqueFloors = results.map((row) => row.floors_map_id);
              return resolve(uniqueFloors);
            }
          }
        );
      }
    });
  });
}

function getConnectionsByFloor(floorId) {
  return new Promise(function (resolve, reject) {
    isRegexEnabled()
      .then(function (regexEnabled) {
        let regexCondition = regexEnabled
          ? "AND not first_name REGEXP '^-?[0-9]+$'"
          : "";

        _db.getConnection("cosmos", function (err, conn) {
          if (err) {
            return reject(err);
          }

          if (conn) {
            conn.query(
              `SELECT 
                    c.first_name AS first_name,
                    c.id_login AS id_login,
                    c.extension AS extension,
                    c.reason AS reason,
                    c.state AS state,
                    c.skill AS skill,
                    c.time AS time,
                    c.platform AS platform,
                    (GROUP_CONCAT(CONCAT(c.priority, '||',LOWER(c.skill)))) AS list_skills
                FROM 	
                  cells_map cm 
                LEFT JOIN connections_map c ON c.extension = cm.extension
                WHERE 
                  cm.floors_map_id = ${floorId}
                  AND cm.extension is not null
                  AND c.first_name is not null
                  ${regexCondition}
                GROUP BY c.id_login`,
              function (error, results) {
                conn.release();
                return error ? reject(new Error(error)) : resolve(results);
              }
            );
          }
        });
      })
      .catch(function (error) {
        reject(error);
      });
  });
}

function isRegexEnabled() {
  return new Promise(function (resolve, reject) {
    _db.getConnection("jarvis", function (err, conn) {
      if (err) {
        return reject(err);
      }

      if (conn) {
        conn.query(
          `SELECT valor FROM jarvis_configuracion_general WHERE nombre = "maps::connections::regex_numeric"`,
          function (error, results) {
            conn.release();
            if (error) {
              return reject(new Error(error));
            }
            let regexEnabled = results.length > 0 && results[0].valor == "1";
            resolve(regexEnabled);
          }
        );
      }
    });
  });
}

function truncateMapConnections() {
  return new Promise(function (resolve, reject) {
    _db.getConnection("cosmos", function (err, conn) {
      if (err) {
        return reject(err);
      }

      if (conn) {
        conn.query(`TRUNCATE TABLE connections_map`, function (error, results) {
          conn.release();
          return error ? reject(new Error(error)) : resolve(results);
        });
      }
    });
  });
}

module.exports = {
  save_chunk,
  delete_data,
  get_integration_credential_list,
  connection_map_data_validation,
  get_integration_api_token,
  delete_basic_data_user,
  save_chunk_basic_data_user,
  getExternalExtensionsByPlatformID,
  getUniqueFloors,
  getConnectionsByFloor,
  truncateMapConnections,
  convertEmptyToNull
};
