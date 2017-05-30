<!DOCTYPE html>

<body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.1/socket.io.js"></script>
<script>
    socket = io.connect('http://localhost:3000');
    socket.on('connect', function(msg){
        console.log("connected to server");
    });
    socket.on("disconnect", function(){
        console.log("client disconnected from server");
    });
</script>
</body>