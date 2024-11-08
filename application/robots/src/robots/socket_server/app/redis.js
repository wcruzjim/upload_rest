const APP_CONFIG = require('../../../config/config');
const redis = require('redis').createClient(APP_CONFIG.REDIS);
const debug = require('debug')('app:redis');


redis.on('error', function(err){
  debug('Error redis connection',err);
});

redis.connect().then(() => {
  // not implemented
}).catch((err) => {
  debug(err.message);
})

module.exports = redis;
