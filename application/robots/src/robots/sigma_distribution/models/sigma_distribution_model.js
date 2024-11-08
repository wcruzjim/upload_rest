const general_helper = require('../../../helpers/general');
const oracledb = require('oracledb');
oracledb.outFormat = oracledb.OUT_FORMAT_OBJECT;
const axios = require('axios');
const CONFIG = require('../../../config/config');



async function getDbConnection(genesys_credentials) {

    let con = await oracledb.getConnection({
      user:genesys_credentials.user,
      password:genesys_credentials.password,
      connectString:genesys_credentials.connectString
    })
    .catch(function (err) {
        general_helper.handleUncaughtExceptions(err);
        return false;
    });
    general_helper.showDetails(`${genesys_credentials.database} Database connected`);
    return (con);
  }

  function format_integration_credentials(integration_credentials){
    return integration_credentials.map(credentials => {
       if (credentials.motor === 'oracle') {
        const connectString = `(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=${credentials.host})(PORT=${credentials.port}))(CONNECT_DATA=(SERVICE_NAME=${credentials.database})))`;
        return {
          database: credentials.database,
          integration_id:credentials.integration_id,
          user:credentials.user,
          password:credentials.password,
          connectString
        }
       }
       return {
        integration_id:credentials.integration_id,
        user:credentials.user,
        password:credentials.password,
        host:credentials.host,
        port:credentials.port,
        database:credentials.database
       }
   
    })
  }


  function getDistributionForSigma(api_token){

    return new Promise(function(resolve, reject){
        axios.get(`${CONFIG.integrations.api.url}/Gtr/getDistributionForSigma`, {
            headers: {
            Authorization: api_token
            },
            httpsAgent: new (require('https')).Agent({ rejectUnauthorized: false })
        })
        .then(response => {
            if (response.data == undefined || response.data.result == undefined){
            general_helper.showDetails(response);
            return reject(new Error('Error trying to get distribution for Sigma'));
            }
            resolve(response.data.result);
        })
        .catch((error) => {
            general_helper.showDetails(error);
            reject(new Error('Error trying to get distribution for Sigma'));
        });
    });
}
  
  

async function insertDistributionSigma(data, genesys_credentials){

    if(data == undefined || data.length < 1){
        general_helper.showDetails(`Empty data to insert on ${genesys_credentials.database} DB`);
        return true;
    }

    let connection = await getDbConnection(genesys_credentials);

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
        general_helper.showDetails(`Error to insert data into ${genesys_credentials.database} DB, ${err}`);
        return false;
    });
    connection.close();
    return result.rowsAffected;
}



async function deleteDistributionSigma(genesys_credentials){

    let connection = await getDbConnection(genesys_credentials);

    if(connection === false){
        return [];
    }

    let options = {autoCommit: true};

    const result = await connection.execute(
        `DELETE FROM genesys_sof.reptetl_usuario_pcrc`,
        [],
        options
    )
    .catch(function(err){
        general_helper.handleUncaughtExceptions(err);
        connection.close();
        return [];
    });
    connection.close();
    return result.rowsAffected;
}

//  get users in specific charge who has permissions to the target PCRC
function getParentChargesByPattern(data, api_token){
	
	if(!data.pattern || !data.target){
		return Promise.reject('Pattern or target not valid');
	}

	data.target = data.target.constructor === Array ? data.target : [data.target];

  return new Promise(function(resolve, reject){
      axios.post(`${CONFIG.integrations.api.url}/Gtr/getParentPositionsByPattern`,{
        data
      }, {
        headers: {
          Authorization: api_token
        },
        httpsAgent: new (require('https')).Agent({ rejectUnauthorized: false })
      })
      .then(response => {
        if (response.data == undefined || response.data.result == undefined){
          general_helper.showDetails(response);
          return reject(new Error('Error trying to get parent positions by pattern'));
        }
        resolve(response.data.result);
      })
      .catch((error) => {
        general_helper.showDetails(error);
        reject(new Error('Error trying to get parent positions by pattern'));
      });
    });
  }


module.exports = {
    format_integration_credentials,
    getDistributionForSigma,
    insertDistributionSigma,
    deleteDistributionSigma,
    getParentChargesByPattern
}
