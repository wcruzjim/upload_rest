const path = require('path');
const controller_connection = require(path.resolve(__dirname,'../../controllers/root/connection'));

function events(socket){

  var actions = controller_connection(socket);
  socket.on('disconnect', actions.user_disconnect);
}

module.exports = events;
