process.env.NODE_TLS_REJECT_UNAUTHORIZED = '0';
const express = require('express');
const APP_CONFIG = require('../../config/config.js');
const app = express();
var https;
if(process.env.ENVIRONMENT != 'production'){
  https = require('http');
}
else {
  https = require('https');
}
const fs = require('fs');
const configApp = require('./app/server.js').createServer;
const configSockets = require('./app/sockets.js').createServerSockets;
const numCPUs = require('os').cpus().length;
const debug = require('debug')('app:server');

debug(`Start server`);
// we will pass our 'app' to 'https' server
var server;
if(process.env.ENVIRONMENT != 'production'){
  server = https.createServer({ }, app);
}
else {
  server = https.createServer({
    key: fs.readFileSync('./resources/ssl/key.key'),
    cert: fs.readFileSync('./resources/ssl/cert.cer'),
    requestCert: false,
    rejectUnauthorized: false
  }, app);
}

var io = configSockets(server);
configApp(app, io);

server.listen(APP_CONFIG.integrations.port, function(){

  debug(`Server listening on port ${APP_CONFIG.integrations.port}`);
});
