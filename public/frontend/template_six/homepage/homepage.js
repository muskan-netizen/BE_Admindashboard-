$(document).ready(function(){
    // $(".time-circle").click(function () {
    //     if ($("body").addClass("dark")) {
    //     //$("#fullpage").removeClass("night");
    //     //$("#switch").removeClass("switched");
    //     }
    //     else {
    //     $("body").removeClass("dark");
    //     //$("#switch").addClass("switched");
    //     }
    // });


    $(document).on('click','#dark_mode_switch',function(){
        alert();
        var mode = $(this).attr('data-mode');
        if(!$('body').hasClass(mode)) {
            $('body').addClass(mode);
        }
        // $('body').addClass(mode);
        // if(mode == 'light') {
        //     $('body').addClass(mode);
        // } else {

        // }
    });
});


