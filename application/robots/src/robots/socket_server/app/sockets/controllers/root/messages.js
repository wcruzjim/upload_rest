const path = require("path");
const helper_logs = require(path.resolve(__dirname,'../../../../../../helpers/logs'));

function actions(socket){


  function message(data){

    if(data == undefined || data.target == undefined || data.pattern == undefined){
      return;
    }

    if(data.target.constructor == Array){
      data.target = data.target.map(function(target){
        return data.pattern + "_" + target;
      });
    }
    else{
      data.target = [data.pattern + "_" +data.target];
    }

    // Attach multiple targets
    socket._rooms = data.target;

    // Attach the origin username sender
    data.username_origin  = socket.handshake.query.username != undefined ? socket.handshake.query.username : undefined;

    let temp_log = Object.assign({
      ip : socket.handshake.address.split(":").slice(-1)[0]
    }, data);

    socket.emit("message", data);
    
    // Default type log is manual notification
    var type_log = 3;

    // If packet type is different to manual notification set type of packets of websocket
    if(data.packet_type === undefined || data.packet_type !== "a"){
      type_log = 14;
    }

    helper_logs.create_log( type_log , temp_log ,'debug', data.username_origin);
  }



  function message_user_by(data){

    if(data == undefined || data.target == undefined || data.pattern == undefined){
      return;
    }

    if(data.target.constructor == Array){
      data.target = data.target.map(function(target){
        return data.pattern + "_" + target;
      });
    }
    else{
      data.target = [data.pattern + "_" +data.target];
    }

    // Attach multiple targets
    socket._rooms = data.target;

    // Attach the origin username sender
    data.username_origin  = socket.handshake.query.username != undefined ? socket.handshake.query.username : undefined;

    let temp_log = Object.assign({
      ip : socket.handshake.address.split(":").slice(-1)[0]
    }, data);

    socket.emit("message", data);

    if(data.skip_log === true){
      return;
    }

    // Default type log is manual notification
    var type_log = 3;

    // If packet type is different to manual notification set type of packets of websocket
    if(data.packet_type === undefined || data.packet_type !== "a"){
      type_log = 14;
    }

    helper_logs.create_log(type_log, temp_log ,'debug', data.username_origin);
  }


  function message_all(data){
    socket.broadcast.emit("message", data);
  }


  return {
    message,
    message_user_by,
    message_all
  }

}

module.exports = actions;
