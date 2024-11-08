const path = require('path');
var redis = require(path.resolve(__dirname,'../../../redis'));

function actions(socket){

  function user_disconnect(){
    redis.HDEL('sockets', socket.id);
  }

  return {
    user_disconnect
  }

}

module.exports = actions;
