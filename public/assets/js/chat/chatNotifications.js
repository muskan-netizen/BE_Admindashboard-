//(async function(){
    

// var socket = new io('https://chat.royoorders.com');

// await socket.connect(); 

// // Add a connect listener

// // Add a connect listener
socket.on('new-message',function(data) {
    //newMessage(data)
    //alert();
    console.log('Received a message from the server!',data);
});
// // Add a disconnect listener
socket.on('disconnect',function() {
 console.log('The client has disconnected!');
});

//})()