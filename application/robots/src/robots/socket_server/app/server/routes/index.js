const route_messages = require('./notifications');
const route_util = require('./util');

function routes(app){

    app.use('/api', route_messages);
    app.use('/util', route_util);

}

module.exports = routes;