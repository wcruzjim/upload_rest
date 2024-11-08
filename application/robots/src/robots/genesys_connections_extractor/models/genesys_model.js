const teo_rest_communication = require('../../../models/api_connection_map');
const general_helper = require('../../../helpers/general');
const oracledb = require('oracledb');
oracledb.outFormat = oracledb.OUT_FORMAT_OBJECT;

async function getDbConnection(genesys_credencials) {

  let con = await oracledb.getConnection({
    user:genesys_credencials.user,
    password:genesys_credencials.password,
    connectString:genesys_credencials.connectString
  })
    .catch(function (err) {
      general_helper.handleUncaughtExceptions(err);
      return false;
    });
  return (con);
}

async function get_connected_status(genesys_credencials) {

  let connection = await getDbConnection(genesys_credencials);

  if (connection === false) {
    return [];
  }

  const result = await connection.execute(
    `SELECT
          round((current_date - (agsh.added-5/24)) * 24 *60 * 60) AS seconds,
          ag.username AS firstname,
          skl.slevel AS skill_level,
          sk.name AS skill_name,
          agsh.state AS state,
          lc.logincode AS login,
          endp.dn AS extension,
          ag.id AS agentid
          FROM ( SELECT DISTINCT subsn.loginsessionid, subsn.agentid, subsn.terminated, subsn.placeid, subsn.endpointid FROM genesys_icon.gx_session_endpoint subsn WHERE subsn.state = 1 ) sn
          JOIN (SELECT agt.status, agt.id, agt.username FROM genesys_icon.gc_agent agt WHERE agt.status = 1 ) ag ON ag.id = sn.agentid
          LEFT JOIN ( SELECT sklt.slevel, sklt.agentid, sklt.skillid FROM genesys_icon.gcx_skill_level sklt WHERE sklt.STATUS = 1 ) skl ON skl.agentid = sn.agentid
          LEFT JOIN genesys_icon.gc_skill sk ON sk.id = skl.skillid
          JOIN genesys_icon.g_agent_state_history agsh ON agsh.loginsessionid = sn.loginsessionid AND agsh.id = ( SELECT max(sagsh.id) FROM genesys_icon.g_agent_state_history sagsh WHERE sagsh.loginsessionid = sn.loginsessionid )
          LEFT JOIN genesys_icon.gc_login lc ON lc.id = agsh.loginid
          LEFT JOIN genesys_icon.gc_endpoint endp ON endp.id = sn.endpointid
          WHERE skl.agentid IS NOT NULL
      
          UNION ALL 
      
          SELECT
              round((current_date - (agsh.added-5/24)) * 24 *60 * 60) AS seconds,
              ag.username AS firstname,
              skl.slevel AS skill_level,
          ( SELECT grp.name FROM genesys_icon.gc_group grp WHERE grp.id =  (SELECT tmpga.groupid FROM genesys_icon.gcx_group_agent tmpga WHERE tmpga.agentid =  sn.agentid AND tmpga.deleted IS NULL AND ROWNUM = 1)  ) skill_name,
              agsh.state AS state,
              lc.logincode AS login,
              endp.dn AS extension,
              ag.id AS agentid
          FROM ( SELECT DISTINCT subsn.loginsessionid, subsn.agentid, subsn.terminated, subsn.placeid, subsn.endpointid FROM genesys_icon.gx_session_endpoint subsn WHERE subsn.state = 1 ) sn
          JOIN (SELECT agt.status, agt.id, agt.username FROM genesys_icon.gc_agent agt WHERE agt.status = 1 ) ag ON ag.id = sn.agentid
          LEFT JOIN ( SELECT sklt.slevel, sklt.agentid, sklt.skillid FROM genesys_icon.gcx_skill_level sklt WHERE sklt.STATUS = 1 ) skl ON skl.agentid = sn.agentid
          LEFT JOIN genesys_icon.gc_skill sk ON sk.id = skl.skillid
          JOIN genesys_icon.g_agent_state_history agsh ON agsh.loginsessionid = sn.loginsessionid AND agsh.id = ( SELECT max(sagsh.id) FROM genesys_icon.g_agent_state_history sagsh WHERE sagsh.loginsessionid = sn.loginsessionid )
          LEFT JOIN genesys_icon.gc_login lc ON lc.id = agsh.loginid
          LEFT JOIN genesys_icon.gc_endpoint endp ON endp.id = sn.endpointid
          WHERE skl.agentid IS NULL
      `
  )
    .catch(function (err) {
      general_helper.handleUncaughtExceptions(err);
      connection.close();
      return [];
    });
  connection.close();
  return result.rows;

}

function format_integration_credentials(integration_credentials){
  return integration_credentials.map(credentials => {
     if (credentials.motor === 'oracle') {
      const connectString = `(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=${credentials.host})(PORT=${credentials.port})))(CONNECT_DATA=(SID=${credentials.database})(SERVER=DEDICATED)))`;
      return {
        database:credentials.database,
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

function format_random_fingerprint(integration_credentials){
  return integration_credentials.map(credentials => {
    return {
      integration_id:credentials.integration_id,
      random_fingerprint:null
    }
  });

}

function format_connection_result(connections, fingerprint, random_fingerprint, dict_status) {
  return connections.map(element => {
    return {
      skill: element.SKILL_NAME,
      first_name: element.FIRSTNAME,
      id_login: element.LOGIN,
      extension: element.EXTENSION,
      state: dict_status.hasOwnProperty(element.STATE) ? dict_status[element.STATE] : 'Desconocido',
      current_skill: element.SKILL_NAME,
      time: element.SECONDS,
      priority: element.SKILL_LEVEL,
      finger_print: parseInt(fingerprint),
      platform: 'genesys',
      unique_finger_print: random_fingerprint
    }
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
  format_integration_credentials,
  format_random_fingerprint,
  format_connection_result,
  send_chunks_inserts
}
