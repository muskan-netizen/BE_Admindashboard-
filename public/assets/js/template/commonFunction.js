
$(function(){
    // Scrolls the top of the page and adds a. header class
    $(window).scroll(function() {
        var scroll = $(window).scrollTop();

        if (scroll >= 100) {
            $(".header").addClass("darkHeader");
        } else {
            $(".header").removeClass("darkHeader");
        }
    });


    // Update the location by selected city
    $(document).on("click", ".updateLocationByCity", function (e) {
        var $this = $(this);
        var lat = $this.attr('data-lat');
        var long = $this.attr('data-long');
        var address = $this.attr('data-address');
        if(!lat || !long){
            return;
        }
        console.log(lat);
        changeLocationByCity(lat,long,address);
    });

    // AOS.init();
    // Changes the image.
    function changeImage(image, check) {
        var  icon = $(image).attr('data-icon');
        var  icon_two = $(image).attr('data-icon_two');
        if(check == 1)
        {
        setTimeout(function () {
            $(image).attr('data-src',icon_two);
            $(image).attr('src',icon_two);
        },200);
        }else if(check == 0){
            setTimeout(function () {
                $(image).attr('data-src',icon);
                $(image).attr('src',icon);
            },200);

        }
    }

    // Change the location by  city lat long

    function changeLocationByCity(lat,long,address){
    
        let url = `/updateLocation?latitude=${lat}&&longitude=${long}&&address=${address}` ;
        window.location.href = url;
        
    }

})