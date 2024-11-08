const CONFIG = require('../config/config');
const mysql = require("mysql");

const _db = mysql.createPoolCluster({
  restoreNodeTimeout : 5000,
  connectionLimit : 10
});

// Add connection
_db.add('cosmos', CONFIG.integrations.databases.cosmos);
_db.add('jarvis', CONFIG.integrations.databases.jarvis);



module.exports = {
  _db
}
