const events_root = require('./root/index');

function attach_events(io){

  // Events NameSpace '/'
  events_root(io);
}

module.exports = attach_events;
