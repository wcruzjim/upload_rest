const express = require('express');
const routes = require('./server/routes/index');
const errorHandler = require('./server/middlewares/errorhandler');
const debug = require('debug')('app:api');
const attachSocket = require('./server/middlewares/attachSocket');

function createServer(app, io){

  
  debug('Attach server configurations');
  
  app.use(express.urlencoded({extended : false}));
  app.use(express.json());

  // Attach socket instance to all incoming request
  attachSocket(app, io);

  debug('Attach server routes');
  
  // Routes
  routes(app);
  // 401 Unauthorized Error
  app.use(errorHandler.error_401);
  // 404 not found resource  
  app.use(errorHandler.error_404);
  // uncaughtException
  app.use(errorHandler.uncaughtException);

  return app;
}


module.exports = {
  createServer
};
