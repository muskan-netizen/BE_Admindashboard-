

    //await getALLchat();
    $(document).on('click','.fetchChat',async function(){
        var roomId = $(this).attr('data-id');
        var roomName = $(this).attr('data-roomName');
       
        if(!roomId){
            return;
            $('#chatHistory').removeClass('room_'+roomId);
        }
        $('#roomName').html(roomName);
        await getALLchat(roomId);
    });

    async function getChatRooms(){
        var html='';
        axios.get(`https://chat.royoorders.com/api/room`)
        .then(async response => {
            // console.log(response.data);
             if(response.status == 200) {
                if(response.data.length > 0) {
                   await response.data.forEach(function (room) {
                        html+= `<div id="room_${room._id}" data-id="${room._id}" data-roomName="${room.room_name}" class="chat-list-item d-flex align-items-start rounded bg-white fetchChat">`;
                        html+= `<div class="align-self-center mr-3">`;
                        html+= `<div class="rounded-circle bg-gray" style="width: 8px; height: 8px; opacity: 0;"></div>`;
                        html+= `</div>`;
                        html+= `<div class="align-self-center mr-3">`;
                        html+= `<div class="overflow-hidden rounded-circle">`;
                        html+= `<svg width="32" height="32" viewBox="0 0 1651 1651" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="1651" height="1651" rx="14" fill="white"></rect><path d="M495.286 1098.96L497.967 1070.86L478.04 1050.88C408.572 981.233 368 891.771 368 795.344C368 585.371 565.306 402 826 402C1086.69 402 1284 585.371 1284 795.344C1284 1005.32 1086.69 1188.69 826 1188.69V1248.69L825.913 1188.69C779.837 1188.75 733.952 1182.77 689.432 1170.9L667.26 1164.98L646.8 1175.37C620.731 1188.61 562.74 1213.98 467.32 1235.35C480.554 1191.83 490.95 1144.39 495.286 1098.96Z" stroke="url(#paint0_linear)" stroke-width="120"></path><defs><linearGradient id="paint0_linear" x1="662.312" y1="397.956" x2="416.164" y2="1678.7" gradientUnits="userSpaceOnUse"><stop stop-color="#7514FB"></stop><stop offset="0.624243" stop-color="#F26D41"></stop><stop offset="1" stop-color="#F43B4B"></stop></linearGradient></defs></svg>`;
                        html+= `</div>`
                        html+= `</div>`
                        html+= `<div class="media-body overflow-hidden">`
                        html+= `<h5 class="text-truncate font-size-14 mb-1">#${room.room_name}</h5>`
                        html+= `<p id="preview_message_${room._id}" class="text-truncate mb-0">..</p>`
                        html+= `</div>`
                        html+= `<div class="font-size-11">${room.created_date}</div>`
                        html+= `</div>`;

                    });
                    await $('#chatRooms').html(html);
                } else {
                    $('#chatRooms').html(`No room found!`);
                }

             } else {
                $('#chatRooms').html(`No room found!`);
             }

            
        })
        .catch(e => {
        //this.errors.push(e)
            $('#chatRooms').html(`No room found!`);
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
                        html+= `<div class="d-flex justify-content-between">
                                <div style="flex: 1 1 0%;"></div>
                                <div class="text-right mb-4">
                                    <div class="conversation-list d-inline-block bg-light px-3 py-2" style="border-radius: 12px;">
                                        <div class="ctext-wrap">
                                            <div class="conversation-name text-left text-primary mb-1" style="font-weight: 600;">${data.nickname}</div>
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

                    });
                    $('#rightChat').show();
                    $('#chatHistory').addClass('room_'+roomId);
                    await $('#chatHistory').html(html);
                    scrollDown();
                } else {
                    $('#chatHistory').html(``);
                }

             } else {
                $('#chatHistory').html(``);
             }
        })
        .catch(e => {
        
        })
    }



    async function newMessage(message){
        var data = message.message;
        if(data.message ==  undefined || data.message ==  'undefined'){
            return;
        }
        var html='';
       
        html = `<div class="d-flex justify-content-between">
                <div style="flex: 1 1 0%;"></div>
                <div class="text-right mb-4">
                    <div class="conversation-list d-inline-block bg-light px-3 py-2" style="border-radius: 12px;">
                        <div class="ctext-wrap">
                            <div class="conversation-name text-left text-primary mb-1" style="font-weight: 600;">${data.nickname}</div>
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