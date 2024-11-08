const path = require('path');
const controller = require(path.resolve(__dirname,'../../controllers/root/phoneConnections'));

function events(socket){

  var actions = controller(socket);

  socket.on('subscribeFloor', actions.subscribeFloor);
}

module.exports = events;
