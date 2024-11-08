require('dotenv').config({path:"../../../.env"})

var config = {

  integrations:{

    port : process.env.SOCKETS_PORT || 3000,

    databases: {
      cosmos:{
        host: process.env.JARVIS_DB_HOST,
        user: process.env.JARVIS_DB_USER,
        password: process.env.JARVIS_DB_PASSWORD,
        database: process.env.COSMOS_DB_DATABASE
      },
      jarvis:{
        host: process.env.JARVIS_DB_HOST,
        user: process.env.JARVIS_DB_USER,
        password: process.env.JARVIS_DB_PASSWORD,
        database: process.env.JARVIS_DB_DATABASE
      },
      rocketchat : {
        host :  process.env.ROCKET_DB_HOST,
        database : process.env.ROCKET_DB_DATABASE
      },
      prodm2 : {
        user          : process.env.SIGMA_DB_USER,
        password      : process.env.SIGMA_DB_PASSWORD,
        connectString : process.env.SIGMA_DB_CONNECTIONSTRING
      }
    },

    api:{
      url : process.env.TEO_REST_URL,
      user: process.env.TEO_REST_USER,
      password: process.env.TEO_REST_PASSWORD,
      token_refresh_time: 900000
    },

    logs : {
      // Enable or disable saving logs
      active : true,
      // interval of time to wait before saving logs in the queue
      interval_save_logs : 20000 //  milliseconds
    },

    automatic_notifications : {
      // Websocket server to send notifications
      sockets_host : process.env.SOCKETS_HOST,
      sockets_port : process.env.SOCKETS_PORT,
      // Enable or disable sending notifications
      active : true,
      // interval of time to get agent's connections from db and perform the match
      interval_get_connections : 1000, // 1 second
      // interval of time to wait before send a notification to an specific user
      interval_send_notification : 60000, // 60 seconds
      // interval of time to get list of users uniques
      interval_get_users_unique : 600000, // 10 minutes
      // Enable or disable saving logs of notifications sent
      save_logs : true,
      // ID of automatic notifications log type
      automatic_notifications_type_log : 4,
      // interval of time to wait before save notification logs
      interval_save_logs : 30000, // 30 seconds
      // Enable or disable clear logs sent
      clear_notification_logs : true,
      // interval of time to wait before  clear notification logs
      interval_clear_notification_logs : 300000, // 5 minutes
      // how many hours storage notification logs data before delete
      hours_storage_notification_logs : 6 // last 6 hours
    },
    map_connections: {
      interval_get_connections : 4000, // 4 seconds
      interval_truncate_table : 300000 // 5 minutes
    },
    historic_phone_connections : {
      interval_collect_data : 60, // seconds
      interval_collect_adherence : 60, // seconds
      data_retention : 1 // Data retention days
    },
    
    redis : {
      // redis host
      host : '172.102.180.192',
      // redis port
      port : 6379
    },

    sockets : {
      valid_origins : ['cosmos_server', 'cosmos_client','cosmos_worker'],
      initial_rooms : ['id_avaya','id_genesys','username','identify','operation_internal_code','source','machine', 'pcrc','ceco']
    },

    server: {
      /***************/
      /** RATE LIMIT  **/
      /***************/
      rate_limit : {
          windowMs: 1 * 60 * 1000, // 1 minutes
          max: 600 // limit how many request per ip in windowMs
      },
      /***************/
      /****  TOKEN  ***/
      /***************/
      jwt : {
        secret : 'tXpfEafFnzcYKX8dpcMqq4zzASx89jTErQDy7b6xAjzhhafku9w8rUt4aeNHbqYnu9hjNNZWuuyu2zJYQMffA3XJ3erqwzQXHxExBVXuFgjLvM3cbZQjdBC9EAgk4mad',
        algorithms : ['HS256','HS384','HS512']
      }
    },

    rpa:{
      jwt_jarvis : process.env.JARVIS_JJS,
      jwt_recovery_jarvis : process.env.JARVIS_JJSR,
      jarvis_domain : process.env.TEO_REST_URL.split("/").length >= 2 ? process.env.TEO_REST_URL.split("/")[2] : "",
      jarvis_url : process.env.TEO_REST_URL.split("/").slice(0, process.env.TEO_REST_URL.split("/").length - 1).join("/") + process.env.JARVIS_SUBPATH_FRONT
    }
  }
}

module.exports = config;


