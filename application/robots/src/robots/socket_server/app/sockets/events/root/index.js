const events_connections = require('./connection');
const events_messages = require('./messages');
const events_user_statistics = require('./user_statistics');
const events_workers = require('./workers');
const events_phoneConnections = require('./phoneConnections');
const middleware_connection = require('../../middlewares/root/connection');
const middleware_rooms = require('../../middlewares/root/rooms');
const helper_sockets_statistics = require('../../../../../../helpers/sockets_statistics');

function attach_events_root(io){

  // Get count of connected sockets on interval
  helper_sockets_statistics.get_count_connected_sockets(io.of('/'));

  // Validate socket is identified (JWT, Username, Identify, Operacion)
  io.of('/').use(middleware_connection.user_connection);

  // Join socket to initial rooms (Identify, Id login, Username)
  io.of('/').use(middleware_rooms.initial_rooms);

  // Attach all the events once connected
  io.of('/').on('connection', function(socket){

    // Register a user into redis
    middleware_connection.redis_register(socket);

    // Attach events to socket
    events_connections(socket);
    // Attach events to socket
    events_messages(socket);
    // Attach events to socket
    events_user_statistics(socket);
    // Attach events to socket
    events_workers(socket);
    // Attach events to socket
    events_phoneConnections(socket);

  });

}

module.exports = attach_events_root;
