const path = require('path');
const APP_CONFIG = require(path.resolve(__dirname,'../../../../../../config/config'));
const redis = require(path.resolve(__dirname,'../../../redis'));
const debug = require('debug')('app:middlewareSocket');

function user_connection(socket, next){

  try{

    // Check if is not spcified a source in the handshake
    if(socket.handshake.query.source == undefined){
      debug('SOCKET_IO: Conexión rechazada. Se debe de especificar un origen de conexión [/]');
      return next(new Error("Conexión rechazada. Se debe de especificar un origen de conexión [/]"));
    }
    // check if origin is a valid origin
    if(APP_CONFIG.integrations.sockets.valid_origins.indexOf(socket.handshake.query.source) == -1){
      debug('SOCKET_IO: Conexión rechazada. El origen de conexión no es valido (' + socket.handshake.query.source + '). Origenes validos [' + APP_CONFIG.SOCKETS.VALID_ORIGINS.join(',') + ']' );
      return next(new Error("El origen de conexión no es valido"));
    }

  }
  catch(err){
    debug('SOCKET_IO: Conexión rechazada. Error al intentar analizar el origen');
    return next(new Error("Error al analizar los parametros adicionales de handshake"));
  }

  next();
}


// Register a new socket into redis
function redis_register(socket){

  let new_user = {};

  new_user["date_create"] = Date.now();

  if(socket.hasOwnProperty("id")){
    if(socket.id.length > 0) new_user["id_socket"] = socket.id;
  }
  
  var handshake_keys = Object.keys(socket.handshake.query);

  handshake_keys.forEach(function(current_key){
    if(socket.handshake.query[current_key].length > 0){
      new_user[current_key] = socket.handshake.query[current_key];
    }
  });

  if(socket.handshake.hasOwnProperty("address")){
    if(socket.handshake.address.length > 1){
      new_user["ip"] = socket.handshake.address.split(":").slice(-1)[0];
    }
  }

  redis.HSET('sockets',socket.id, JSON.stringify(new_user));
}



module.exports = {
  user_connection,
  redis_register
};
