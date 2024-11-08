const path = require('path');
const controller_messages = require(path.resolve(__dirname,'../../controllers/root/messages'));

function events(socket){

  var actions = controller_messages(socket);

  socket.on('message', actions.message);
  socket.on('message_user_by', actions.message_user_by);
  socket.on('message_all', actions.message_all);
}

module.exports = events;
