const mongoose = require('mongoose');
const CONFIG = require('../../config/config');
const URL_DB = CONFIG.integrations.databases.rocketchat.host;
const DB_NAME = CONFIG.integrations.databases.rocketchat.database
const connection_string = `mongodb://${URL_DB}/${DB_NAME}`;
const debug = require('debug')('app:rocketChat');
mongoose.connect(connection_string, {
    useNewUrlParser: true,
    useUnifiedTopology: true,
    useFindAndModify: false,
    useCreateIndex: true
});

const db = mongoose.connection;

db.once('open', function() {
    debug('connected DB rocketchat : ' + connection_string );
});