const teo_rest_communication = require('../../../models/api_connection_map');
const general_helper = require('../../../helpers/general');
const {_db} = require('../../../app/databases');


  async function validate_and_save_data(data) {
    if (data){
    let validation = data_validation(data);
      if(!validation){
        throw new Error('Error trying to save connections');
      }
    }
    let array_promises = [];
    const chunk_size = 5000;
    for (let index = 0; index < data.length; index = index + chunk_size) {
      const chunk = data.slice(index, index + chunk_size);
      array_promises.push(save_data_intergrall(chunk));
    }
    const results_all_promises = await Promise.all(array_promises);
    return results_all_promises;
  }


  function data_validation(data) {
    let isValid = true;
    for (const connectionData of data) {
      if (connectionData.usuario_red === undefined || connectionData.usuario_red === null ) {
        general_helper.showDetails(`El valor ${connectionData.usuario_red} no es válido para el campo usuario red. Es requerido y debe de contener al menos 1 caracter`);
        isValid = false;
      }
      if (connectionData.maquina === undefined || connectionData.maquina === null ) {
        general_helper.showDetails(`El valor ${connectionData.maquina} no es válido para el campo maquina. Es requerido y debe de contener al menos 1 caracter`);
        isValid = false;
      }
      if (connectionData.documento === null || connectionData.documento === undefined) {
        general_helper.showDetails(`El valor ${connectionData.documento} no es válido para el campo documento. Es requerido y debe de contener al menos 1 caracter`);
        isValid = false;
      }
    }
    return isValid;
  }


  async function save_data_intergrall(chunk) {
    return new Promise(function(resolve, reject){  
      _db.getConnection("jarvis",function(err,connection){
        if(err){
          general_helper.showDetails(`Error trying to connect to Jarvis DB. ${err}`);
          reject(err);
        }
        if(connection){
          chunk = chunk.map(function(connectionData){
            return [
              convertEmptyToNull(connectionData.usuario_red),
              convertEmptyToNull(connectionData.maquina),
              convertEmptyToNull(connectionData.documento),
              convertEmptyToNull(connectionData.fecha_creacion)
              ];
          })
          connection.query(`INSERT INTO gtr_presencia_client
                            (usuario_red,
                            maquina,
                            documento,
                            fecha_creacion)
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


  async function get_documents_users(list_login_users){
    return new Promise(function(resolve, reject){  
      _db.getConnection("jarvis",function(err,connection){
        if(err){
          general_helper.showDetails(`Error trying to connect to Jarvis DB. ${err}`);
          reject(err);
        }
        if(connection){
          connection.query(`SELECT documento, usuario_red
                            FROM dp_usuarios_red
                            WHERE usuario_red IN (?)`,
                            [list_login_users],
                            function(err, result){
                            connection.release();
                            return (err) ? reject(new Error(err)) : resolve(result);
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


  async function delete_data_intergrall() {
    return new Promise(function(resolve, reject){  
      _db.getConnection("jarvis",function(err,connection){
        if(err){
          general_helper.showDetails(`Error trying to connect to Jarvis DB. ${err}`);
          reject(err);
        }
        if(connection){
          connection.query(`TRUNCATE TABLE gtr_presencia_client`,
                            function(err){
                            connection.release();
                            return (err) ? reject(new Error(err)) : resolve(true);
                          });
        }
      });
    });
  }


  module.exports = { 
    validate_and_save_data,
    save_data_intergrall,
    get_documents_users,
    delete_data_intergrall
  }