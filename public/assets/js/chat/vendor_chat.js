

    //await getALLchat();
    $(document).on('click','.start_chat',async function(){
        var vendor_order_id = $(this).attr('data-vendor_order_id');
        var vendor_id = $(this).attr('data-vendor_id');
        var order_id = $(this).attr('data-order_id');
       
        if(!vendor_order_id && !vendor_id && !order_id){
            return;
            
        }
        $('#order_list_order').show();
        await startChat(vendor_order_id,vendor_id,order_id);
    });

    $(document).on('click','.fetchChat',async function(){
        var roomId = $(this).attr('data-id');
        var roomName = $(this).attr('data-roomName');
        var roomIDn = $(this).attr('data-roomID');
       
        if(!roomId){
            $('#chatHistory').removeClass('room_'+roomId);
            return;
            
        }
        $('#roomName').html(roomIDn);
        await getALLchat(roomId);
    });

    $(document).on('click','.join_room',async function(){
        var room_id = $(this).attr('data-id');
        // var vendor_id = $(this).attr('data-vendor_id');
        // var order_id = $(this).attr('data-order_id');
       
        if(!room_id){
            return;
            
        }
        //$('#order_list_order').show();
        await JoinRoom(room_id);
    });

    $(document).on('click','.send_message',async function(){
        var room_id = $(this).attr('data-id');
         var message = $('#message_box').val();
        // var vendor_id = $(this).attr('data-vendor_id');
        // var order_id = $(this).attr('data-order_id');
       
        if(!room_id || !message){
            return;
            
        }
        //$('#order_list_order').show();
        await sendMessage(message,room_id);
    });


    async function startChat(vendor_order_id,vendor_id,order_id){

        axios.post(`/client/chat/startChat`, {
            sub_domain: window.location.origin,
            client_id:  1,
            db_name:Auth.database_name,
            user_id:  Auth.auth_id,   
            type:'vendor_to_user',
            vendor_order_id:vendor_order_id,
            vendor_id:vendor_id,
            order_id:order_id      
        })
        .then(async response => {
             console.log(response.data.status);
             $('#order_list_order').hide();
             if(response.data.status === true) {
                var data = response.data;
                window.location.href = `/client/chat/${data.roomData._id}`;
                
             } else {
                Swal.fire(
                    'error',
                    'Something went wrong, try again later!',                                    
                    
                )
             }

            
        })
        .catch(e => {
            Swal.fire(
                'Something went wrong, try again later!',                                    
                'error'
            )
        })
    }

    async function getALLchat(roomId){
        var html='';
        axios.get(`https://chat.royoorders.com/api/chat/${roomId}`)
        .then(async response => {
            console.log(response);
            if(response.status == 200) {
                if(response.data.length > 0) {
                   await response.data.forEach(function (data) {
                    var className= 'left-message';
                    var flex = '';
                    if( Auth.auth_id == data.from_user_id && data.from_message == "from_vendor") {
                         className= 'right-message';
                         //flex = '<div style="flex: 110%;"></div>';
                    }
                    // <div class="conversation-name text-left text-primary mr-4" style="font-weight: 600;">${data.email}</div>
                    // <p class="chat-time m-0 p-0" >
                    //                             <svg width="12" height="12" class="prefix__MuiSvgIcon-root prefix__jss80 prefix__MuiSvgIcon-fontSizeLarge" viewBox="0 0 24 24" aria-hidden="true">
                    //                                 <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"></path>
                    //                                 <path d="M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z"></path>
                    //                             </svg> ${data.created_date}</p>

                        html+= `<div class=" ${className}">
                                ${flex}
                                <div class="mb-4">
                                    <div class="conversation-list d-inline-block px-3 py-2" style="border-radius: 12px;">
                                        <div class="ctext-wrap">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="conversation-name text-left text-primary mr-4" style="font-weight: 600;">Yog Raj</div>
                                                <p class="chat-time m-0 p-0" >
                                                <svg width="12" height="12" class="prefix__MuiSvgIcon-root prefix__jss80 prefix__MuiSvgIcon-fontSizeLarge" viewBox="0 0 24 24" aria-hidden="true">
                                                    <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"></path>
                                                    <path d="M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z"></path>
                                                </svg> 4:30am, Today</p>
                                            </div>
                                            
                                            <p class="text-left">${data.message}</p>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>`;

                    });
                    $('#rightChat').show();
                    $('#chatHistory').addClass('room_'+roomId);
                    $('.join_room').attr('data-id',roomId);
                    $('.send_message').attr('data-id',roomId);
                    await $('#chatHistory').html(html);
                    scrollDown();
                } else {
                    $('#chatHistory').html(``);
                    $('.join_room').attr('data-id',roomId);
                    $('.send_message').attr('data-id',roomId);
                    $('#rightChat').show();
                    $('#chatHistory').addClass('room_'+roomId);
                }

             } else {
                $('#chatHistory').html(``);
             }
        })
        .catch(e => {
        
        })
    }



    async function newMessage(message){
        console.log(message);
        var data = message.message.chatData;
        if(data.message ==  undefined || data.message ==  'undefined'){
            return;
        }
        var html='';
        var className= 'left-message';
        var flex = '';
        if( Auth.auth_id == data.from_user_id && data.from_message == "from_vendor") {
             className= 'right-message';
            //  flex = '<div style="flex: 110%;"></div>';
        }
        html = `<div class="d-flex justify-content-between">
                ${flex}
                <div class="text-right mb-4">
                    <div class="conversation-list d-inline-block bg-light px-3 py-2" style="border-radius: 12px;">
                        <div class="ctext-wrap">
                            <div class="conversation-name text-left text-primary mb-1" style="font-weight: 600;">${data.email}</div>
                            <p class="text-left">${data.message}</p>
                            <p class="chat-time mb-0">
                                <svg width="12" height="12" class="prefix__MuiSvgIcon-root prefix__jss80 prefix__MuiSvgIcon-fontSizeLarge" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"></path>
                                    <path d="M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z"></path>
                                </svg> ${data.created_date}</p>
                        </div>
                    </div>
                </div>
            </div>`;
            await $('.room_'+data.room).append(html);
            await $('#preview_message_'+data.room).html(data.message);
            scrollDown();

                  
    }

    

    function scrollDown(){
        var messageBody = document.querySelector('.chatitem');
        messageBody.scrollTop = messageBody.scrollHeight - messageBody.clientHeight;
    }


    async function JoinRoom(room_id) {
        axios.post(`/client/chat/joinChatRoom`, {
            sub_domain: window.location.origin,
            client_id:  1,
            db_name:Auth.database_name,
            user_id:  Auth.auth_id,   
            type:'vendor_to_user',
            room_id:room_id
            //vendor_order_id:vendor_order_id,
            //vendor_id:vendor_id,
            //order_id:order_id      
        })
        .then(async response => {
             console.log(response.data.status);
             if(response.data.status) {
                socket.emit('save-message', { room: room_id, nickname: 'test', message: 'Join this room', created_date: new Date() });
             }
             
        })
        .catch(e => {
            Swal.fire(
                'Something went wrong, try again later!',                                    
                'error'
            )
        })
    
    }

    // function sendMessage(message,room_id){

    //     axios.post(`/client/chat/sendMessage`, {
    //         sub_domain: window.location.origin,
    //         client_id:  1,
    //         db_name:Auth.database_name,
    //         user_id:  Auth.auth_id,   
    //         message:message,
    //         room_id:room_id ,
    //         chattype:'vendor_to_user',
    //         from:'vendor',
    //     })
    //     .then(async response => {
    //          console.log(response.data.status);
    //          if(response.data.status) {
    //             socket.emit('save-message', response.data)
    //             $('#message_box').val('');
    //          }
             
    //     })
    //     .catch(e => {
    //         Swal.fire(
    //             'Something went wrong, try again later!',                                    
    //             'error'
    //         )
    //     })
          
    // }


    function sendMessage(message,room_id){
        // if($data['from'] == 'vendor') {
        //     $messageData = $this->sendSocketMessage($data,$user,'to_user','vendor','from_vendor','vendor_to_user');
        // } else {
        //     $messageData = $this->sendSocketMessage($data,$user,'to_vendor','user','from_user','vendor_to_user');
        // }
        axios.post(`https://chat.royoorders.com/api/chat/sendMessageJoin`, {
            'room_id' : room_id,
            'message': message,
            'user_type': 'vendor',
            'to_message': 'to_user',
            'from_message': 'from_vendor',
            'user_id': Auth.auth_id,
            'email': 'k@k.com',
            //'display_image': $user->image,
            'sub_domain' : window.location.host,
            //'room_name' =>$data->name,
            'chat_type': 'vendor_to_user',
        })
        .then(async response => {
             console.log(response.data.status);
             if(response.data.status) {
                socket.emit('save-message', response.data)
                $('#message_box').val('');
             }
             
        })
        .catch(e => {
            Swal.fire(
                'Something went wrong, try again later!',                                    
                'error'
            )
        })
          
    }