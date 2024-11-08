const JWT_CONFIG = require('../../../config/config').integrations.server.jwt;
const jwt = require('express-jwt');

module.exports = jwt({ 
                                        secret : JWT_CONFIG.secret,  
                                        algorithms :  JWT_CONFIG.algorithms  
                                    });