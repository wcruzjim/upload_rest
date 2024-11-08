const path = require('path');
const controller_actions = require(path.resolve(__dirname,'../../controllers/root/workers'));

function events(socket){

  var actions = controller_actions(socket);

  socket.on('restart_artificial_controller', actions.restart_worker_automatic_notifications);
  socket.on('worker.message', actions.worker_send_packet);
}

module.exports = events;
