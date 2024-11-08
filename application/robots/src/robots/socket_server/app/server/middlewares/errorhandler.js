
const debug = require('debug')('app:errorHandler');

function error_404(res){
    return res.status(404).json({
        statusCode : 404,
        statusText : 'resource not found'
    });
}

function error_401(err, res){

    if (err.name === 'UnauthorizedError') {
        debug(err);
        return res.status(401).json({
            statusCode : 401,
            statusText : 'Invalid token'
        });
    }
}

function uncaughtException(err, res){

    debug(err);
    return res.status(500).json({
        statusCode : 500,
        statusText : 'uncaughtException',
        type : err.type !== undefined ? err.type : ''
    });
}


module.exports = {
    error_404,
    error_401,
    uncaughtException
}