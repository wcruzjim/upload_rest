const path = require('path');
const APP_CONFIG = require(path.resolve(__dirname,'../../../../../../config/config'));

function initial_rooms(socket, next){

  // Read all the initial rooms. And if socket handshake has the key. Join into room

  APP_CONFIG.integrations.sockets.initial_rooms.forEach(function(room){
    if(socket.handshake.query[room] != undefined && socket.handshake.query[room].length > 0){
      socket.join( room + "_" + socket.handshake.query[room]);
    }
  });

  // Continue with other middlewares
  next();
}

module.exports = {
  initial_rooms
};
