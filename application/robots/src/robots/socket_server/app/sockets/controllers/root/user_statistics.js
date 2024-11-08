const path = require('path');
const redis = require(path.resolve(__dirname,'../../../redis'));
const axios = require('axios');
const general_helper = require('../../../../../../helpers/general');
const presence_helper = require('../../../../../../helpers/presencia');
const CONFIG = require('../../../../../../config/config');

function actions(socket){

  // Get info about some sockets.
  //@ Params rooms
  // ['room || idsocket']
  // If @param rooms is null. Then get all the users without operation

  function get_connected_users(rooms,callback){

    // Get all socket's ID from redis adapter
    socket.nsp.adapter.clients(rooms, async function(err, clients){
      if(err){
        return callback([]);
      }

      let datos = await redis.HMGET('sockets', clients, function(err, data){
        data = Object.values(data);

        data = data.map(function(socketdata){
          return JSON.parse(socketdata);
        })
        .filter(function(socketdata){
          return socketdata != undefined && socketdata != null;
        });

        // Get clients without operation
        if(rooms === undefined || rooms === null){
          data = data.filter(function(socketdata){
            return !socketdata.hasOwnProperty('operation_internal_code');
          });
        }

        return data;
      });

      let data = datos.map( (dato) => {
        return JSON.parse(dato);
      });

      general_helper.showDetails(data.length);

      return callback(data);
    });

  }

  // registry beat client
  function beat_client(data){
    if(data != undefined && data != null && data.username != undefined && data.machine != undefined){
      data.timestamp = Date.now();
      redis.HSET('cosmos:client:alive','machine:'+data.machine, JSON.stringify(data));
    }
  }

  function presence_client(presence_user){
    presence_helper.create_presence(presence_user);
  }

  return {
    get_connected_users,
    beat_client,
    presence_client
  }

}

module.exports = actions;
