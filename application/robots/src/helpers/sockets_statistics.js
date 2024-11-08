// Logging the current count connected sockets to an specific nsp
const general_helper = require('./general');

function get_count_connected_sockets(nsp){

  if(nsp == undefined || nsp.name == undefined){
    return;
  }

  nsp.adapter.clients(function(err, clients){

    if(err){
      setTimeout(function(){
        get_count_connected_sockets(nsp);
      },30000);
      general_helper.showDetails('Sockets NSP [' + nsp.name + '] Err trying to get connected sockets count ');
      return;
    }
    general_helper.showDetails('Sockets NSP [' + nsp.name + '] Connected : ' + clients.length);
    setTimeout(function(){
      get_count_connected_sockets(nsp);
    },30000);
    return;
  });

}


module.exports = {
  get_count_connected_sockets
}
