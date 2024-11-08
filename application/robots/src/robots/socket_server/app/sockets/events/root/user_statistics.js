const path = require('path');
const controller_user_statistics = require(path.resolve(__dirname,'../../controllers/root/user_statistics'));

function events(socket){

  var actions = controller_user_statistics(socket);

  socket.on('get_connected_users', actions.get_connected_users);
  socket.on('beat_client', actions.beat_client);
  socket.on('presence_client', actions.presence_client);
}

module.exports = events;
