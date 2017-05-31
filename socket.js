/**
 * Created by csepm_000 on 07-01-2016.
 */
let app = require('express')();
let http = require('http').Server(app);
let io = require('socket.io')(http);
let Redis = require('ioredis');
let redis = new Redis();
redis.subscribe('websites', function(err, count) {

});
redis.on('message', function(channel, message) {
    message = JSON.parse(message);
    console.log(message);
    io.emit(message.event, channel, message.data);
});
http.listen(3000, function(){
    console.log('Listening on Port 3000');
});