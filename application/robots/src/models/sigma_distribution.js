const CONFIG = require("../config/config");
const oracledb = require('oracledb');
oracledb.outFormat = oracledb.OUT_FORMAT_OBJECT;
const general_helper = require('../helpers/general');

async function getDbConnection(){

    let con = await oracledb.getConnection( {
        user          : CONFIG.integrations.databases.prodm2.user,
        password      : CONFIG.integrations.databases.prodm2.password,
        connectString : CONFIG.integrations.databases.prodm2.connectString
      })
      .catch(function(error){
        general_helper.showDetails(error);  
        return false;
      });
      general_helper.showDetails('Prodm2 Database connected');
    return (con);
}

async function getDistributionSigma(){

    let connection = await getDbConnection();

    if(connection === false){
        return [];
    }

    const result = await connection.execute(
        `SELECT COUNT(*) FROM genesys_sof.reptetl_usuario_pcrc`
    )
    .catch(function(){
        connection.close();
        return [];
    });
    connection.close();
      return result.rows;
}

async function insertDistributionSigma(data){

    if(data == undefined || data.length < 1){
        general_helper.showDetails('Empty data to insert on DB prodm2');
        return true;
    }

    let connection = await getDbConnection();

    if(connection === false){
        return [];
    }

    const sql = `INSERT INTO genesys_sof.reptetl_usuario_pcrc VALUES (:empleado, :dmenumerodocumento, :rac_usuario_red, :usuario_genesys, :codigocliente, :cliente, :codigoprograma, :programa, :codigopcrc, :pcrc, :centrocosto, :fecha_conexion_ultimo_pcrc)`;

    const options = {
        autoCommit: true
    };

    const result = await connection.executeMany(sql, data, options)
    .catch(function(err){
        connection.close();
        general_helper.showDetails("Error to insert data into prodm2 DB", err);
        return false;
    });
    connection.close();
     return result.rowsAffected;
}



async function deleteDistributionSigma(){

    let connection = await getDbConnection();

    if(connection === false){
        return [];
    }

    let options = {
        autoCommit: true
    }

    const result = await connection.execute(
        `DELETE FROM genesys_sof.reptetl_usuario_pcrc`,
        [],
        options
    )
    .catch(function(err){
        general_helper.showDetails(err);
        connection.close();
        return [];
    });
    connection.close();
    return result.rowsAffected;
}


module.exports = {
    getDistributionSigma,
    insertDistributionSigma,
    deleteDistributionSigma
}
