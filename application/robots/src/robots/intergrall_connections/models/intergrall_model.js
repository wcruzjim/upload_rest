const teo_rest_communication = require('../../../models/api_connection_map');
const general_helper = require('../../../helpers/general');
const {_db} = require('../../../app/databases');


  async function validate_and_save_data(data) {
    if (data){
    let validation = teo_rest_communication.connection_map_data_validation(data);
      if(!validation){
        throw new Error('Error trying to save connections');
      }
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

  async function delete_data_intergrall(fingerprint) {
    return new Promise(function(resolve, reject){  
      _db.getConnection("cosmos",function(err,connection){
        if(err){
          general_helper.showDetails(`Error trying to connect to Cosmos DB. ${err}`);
          reject(err);
        }
        if(connection){
          connection.query(`DELETE FROM connections_map
                            WHERE finger_print = ?`,
                            fingerprint,
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
    delete_data_intergrall
  }