// Attach io instance to all incoming request
function attachSocket(app, io){
    app.use(function(req, res, next){
        req.io = io;
        next(); 
    });
}

module.exports = attachSocket;