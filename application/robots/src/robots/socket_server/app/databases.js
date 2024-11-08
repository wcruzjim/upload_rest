const APP_CONFIG = require('../../config/config');
const mysql = require("mysql");

const _db = mysql.createPoolCluster({
  restoreNodeTimeout : 5000,
  connectionLimit : 30
});

// add connection
_db.add("cosmos", APP_CONFIG.DATABASES.cosmos);
_db.add("jarvis", APP_CONFIG.DATABASES.jarvis);


// use the var "_db" to execute queries

module.exports = {
  _db
}
