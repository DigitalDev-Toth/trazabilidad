var fs = require('fs'),
    http = require('http'),
    sio = require('socket.io');

var server = http.createServer(function(req, res) {});
server.listen(8000, function() {});
io = sio.listen(server);
// Arreglo Mensajes
var messages = [];
io.sockets.on('connection', function(socket) {
    socket.on('message', function(msg) {
        messages.push(msg);
        io.sockets.emit('message', msg);
        //socket.broadcast.emit('message', msg);
    });


    // Envio mensaje a todos los nuevos clientes // Para caso de COMET en el RIS no se utiliza
    /*messages.forEach(function(msg) {
      socket.send(msg);
    })*/


});
