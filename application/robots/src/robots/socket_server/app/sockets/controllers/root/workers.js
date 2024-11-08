const path = require('path');
const redis = require(path.resolve(__dirname,'../../../redis'));
const debug = require('debug')('app:workers');

function actions(){

  // command automatic notifications worker. To load list config again
  function restart_worker_automatic_notifications(){
    redis.publish('worker:automatic_notifications','restart');
  }
  // command automatic notifications worker. To load list config again
  function worker_send_packet(packet){
    debug("worker_send_packet", packet);
    redis.publish('worker:message',packet);
  }

  return {
    restart_worker_automatic_notifications,
    worker_send_packet
  }
}

module.exports = actions;
