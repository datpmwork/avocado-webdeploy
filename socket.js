/**
 * Created by csepm_000 on 07-01-2016.
 */
var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var Redis = require('ioredis');
var redis = new Redis();
redis.subscribe('keyword', 'system', function(err, count) {

});
redis.on('message', function(channel, message) {
    io.emit('event', message);
});
http.listen(3000, function(){
    console.log('Listening on Port 3001');
});