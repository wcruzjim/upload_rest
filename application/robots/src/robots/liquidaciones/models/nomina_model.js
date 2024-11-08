const general_helper = require('../../../helpers/general');
const {_db} = require('../../../app/databases');


function data_validation(data) {
  
    let isValid = true;
  
    for (const connectionData of data) {
        if (connectionData.documento === undefined || connectionData.documento === null || connectionData.documento.length < 1  ) {
            general_helper.showDetails(`El valor ${connectionData.documento} no es válido para el campo documento.  Debe contener al menos 1 caracter`);
            isValid = false;
        }
        if (connectionData.estado === undefined || connectionData.estado === null ) {
            general_helper.showDetails(`El valor ${connectionData.estado} no es válido para el campo estado. Es requerido y debe ser numérico`);
            isValid = false;
        }
        if (connectionData.id_soporte_registro === undefined || connectionData.id_soporte_registro === null ) {
            general_helper.showDetails(`El valor ${connectionData.id_soporte_registro} no es válido para el campo id_soporte_registro. Es requerido y debe ser numérico`);
            isValid = false;
        }
        if (connectionData.nombre_file === null || connectionData.nombre_file === undefined) {
            general_helper.showDetails(`El valor ${connectionData.nombre_file} no es válido para el nombre_file. Es requerido y debe contener al menos 1 caracter`);
            isValid = false;
        }
    }
  
    return isValid;
  }
  


async function save_chunk(data_files_users) {
    return new Promise(function(resolve, reject){  
      _db.getConnection("jarvis",function(err,connection){
        if(err){
          general_helper.showDetails(`Error trying to connect to Jarvis DB. ${err}`);
          reject(err);
        }
       
        if(connection){
            data_files_users = data_files_users.map(function(connectionData){
            return [
                connectionData.documento == null ? connectionData.documento : connectionData.documento.trim(),
                connectionData.fecha_carga,
                connectionData.estado,
                connectionData.id_jarvis_uploads_tipos,
                connectionData.id_soporte_registro == null ? connectionData.id_soporte_registro : connectionData.id_soporte_registro.trim(),
                connectionData.nombre_file == null ? connectionData.nombre_file : connectionData.nombre_file.trim(),
                connectionData.tipo_file == null ? connectionData.tipo_file : connectionData.tipo_file.trim(),
                connectionData.file
                ];
            });
            connection.query(`INSERT INTO jarvis_uploads
                          ( documento,
                            fecha_carga,
                            estado,
                            id_jarvis_uploads_tipos,
                            id_soporte_registro,
                            nombre_file,
                            tipo_file,
                            file)
                            VALUES ?`,
                        [data_files_users],
                        function(err){
                        connection.release();
                        return (err) ? reject(new Error(err)) : resolve(true);
            });
        }
      });
    });
}

 async function delete_data(type_upload) {

    return new Promise(function(resolve, reject){  
      _db.getConnection("jarvis",function(err,connection){
        if(err){
          general_helper.showDetails(`Error trying to connect to Jarvis DB. ${err}`);
          reject(err);
        }
        if(connection){
          connection.query(`DELETE FROM jarvis_uploads
                            WHERE id_jarvis_uploads_tipos = ?`,
                            type_upload,
                            function(err){
                            connection.release();
                            return (err) ? reject(new Error(err)) : resolve(true);
                          });
        }
      });
    });
  }


module.exports = { 
    save_chunk,
    data_validation,
    delete_data
}
