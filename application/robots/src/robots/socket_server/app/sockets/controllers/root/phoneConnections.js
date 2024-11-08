const path = require("path");
const helper_logs = require(path.resolve(__dirname,'../../../../../../helpers/logs'));

function actions(socket){

  // Subscribe to specific floor room
  function subscribeFloor(floor){

    // if floor is undefined or is not a valid number
    if(floor == undefined || isNaN(parseInt(floor))){
      return false;
    }

    // Get current rooms
    socket.adapter.clientRooms(socket.id, function(err, rooms){
      
      let currentFloorRooms = rooms.filter(function(room){
        return room.indexOf('floor_') !== -1 && room  !== ('floor_' + floor) ;
      });

      currentFloorRooms.forEach(function(room){
        socket.adapter.remoteLeave(socket.id, room);
      });

      socket.adapter.remoteJoin(socket.id,'floor_' + floor);
    });
  }


  return {
    subscribeFloor
  }

}

module.exports = actions;
