//(async function(){
    

// var socket = new io('https://chat.royoorders.com');

// await socket.connect(); 

// // Add a connect listener
socket.on('connect',async function() {
    //await getChatRooms();
    console.log('Client has connected to the server!');
});
// // Add a connect listener
socket.on('new-message',function(data) {
    newMessage(data)
    console.log('Received a message from the server!',data);
});
// // Add a disconnect listener
socket.on('disconnect',function() {
 console.log('The client has disconnected!');
});


socket.on('room-created',function(data) {
    newChatGroup(data)
    console.log('Du doooo',data);
});
//})()