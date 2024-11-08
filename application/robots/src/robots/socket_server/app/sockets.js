const APP_CONFIG = require('../../../config/config');
const socketio = require("socket.io");
const redisAdapter = require('socket.io-redis');
const attach_events = require('./sockets/events/index');
var io = {};

function createServerSockets(server){

  // Instance of Socket IO server
  io = require("socket.io")(server);
  // Redis adapter to scaling
  io.adapter(redisAdapter({ host: APP_CONFIG.integrations.redis.host, port: APP_CONFIG.integrations.redis.port }));

  // Attach events to io instance
  attach_events(io);

  return io;
}

module.exports = {
  createServerSockets,
  io
};
