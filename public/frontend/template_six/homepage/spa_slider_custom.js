$(document).ready(function() {

    $(".alHamBurgerIcon").click(function(){
        $(".alSpaMenuCard").addClass("active");
    });
    $(".alMenuClose").click(function(){
        $(".alSpaMenuCard").removeClass("active");
    });


    $(".regular").slick({
            dots: true,
            infinite: false,
            slidesToShow: 1,
            slidesToScroll: 1,
      });

    $('.alSpaListSlider').slick({
      arrows: false,
      dots: false,
      slidesToShow: 5,
      infinite: false,
      responsive: [{
        breakpoint: 1024,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
        }

      }, {
        breakpoint: 800,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 2,
          infinite: true,

        }
      },  {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          infinite: true,
          autoplay: true,
          autoplaySpeed: 2000,
          centerMode: true,
        }
      }]
    });

    $('.alSLslider').slick({
      arrows: false,
      dots: false,
      slidesToShow: 5,
      slidesToScroll: 1,
      infinite: true,
      centerMode: true,
      autoplay: true,
      autoplaySpeed: 2000,
      responsive: [
      {
        breakpoint: 1400,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
        }

      },{
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        }
      }]
    });

    $('.clientsFeedBackSlider').slick({
      arrows: true,
      dots: true,
      slidesToShow: 1,
      slidesToScroll: 1,
      infinite: true,
      autoplay: true,
      autoplaySpeed: 2000,
    });

    $('.slider').slick({
            dots: false,
            infinite: true,
            speed: 500,
            slidesToShow: 8,
            slidesToScroll: 1,
            autoplay: false,
            autoplaySpeed: 2000,
            arrows: true,
            responsive: [{
            breakpoint: 1400,
            settings: {
               slidesToShow: 6,
            }
            },
            {
               breakpoint: 1199,
               settings: {
                  slidesToShow: 4,
               }
            }]
      });
});