var $status = $('#product__slider .pagingInfo');
var $slickElement = $('.product__slider-thmb');

$slickElement.on('init reInit afterChange', function (event, slick, currentSlide, nextSlide) {
    var moreSlides = $('.product__slider-thmb .slick-slide[aria-hidden="true"]:not(.slick-cloned)').length;
    var i = (typeof (currentSlide) != 'undefined' ? currentSlide : 0) + 1;
    // console.log(i);
    moreSlides = slick.slideCount - i;
    if(moreSlides > 5){
        $status.text(moreSlides + '+ more pictures');
    }
});

$(document).on('ready', function() {

    if ( $('.product__slider-main').length ) {
      var $slider = $('.product__slider-main')
          .on('init', function(slick) {
              $('.product__slider-main').fadeIn(1000);
          })
          .slick({
              slidesToShow: 1,
              slidesToScroll: 1,
              arrows: true,
              autoplay: false,
              lazyLoad: 'ondemand',
              autoplaySpeed: 3000,
              inifinte: false,
              asNavFor: '.product__slider-thmb'
          });

          var $slider2 = $('.product__slider-thmb')
                  .on('init', function(slick) {
                      $('.product__slider-thmb').fadeIn(1000);
                  })
                  .slick({
                      slidesToShow: 5,
                      slidesToScroll: 1,
                      lazyLoad: 'ondemand',
                      asNavFor: '.product__slider-main',
                      dots: true,
                      centerMode: false,
                      focusOnSelect: true,
                      inifinte: false
                  });

       //remove active class from all thumbnail slides
       $('.product__slider-thmb .slick-slide').removeClass('slick-active');

       //set active class to first thumbnail slides
       $('.product__slider-thmb .slick-slide').eq(0).addClass('slick-active');

       // On before slide change match active thumbnail to current slide
       $('.product__slider-main').on('beforeChange', function (event, slick, currentSlide, nextSlide) {
        var mySlideNumber = nextSlide;
        $('.product__slider-thmb .slick-slide').removeClass('slick-active');
        $('.product__slider-thmb .slick-slide').eq(mySlideNumber).addClass('slick-active');
      });


        // init slider
      require(['js-sliderWithProgressbar'], function(slider) {

          $('.product__slider-main').each(function() {

              me.slider = new slider($(this), options, sliderOptions, previewSliderOptions);

              // stop slider
              //me.slider.stop();

              // start slider
              //me.slider.start(index);

              // get reference to slick slider
              //me.slider.getSlick();

          });
      });
        var options = {
          progressbarSelector    : '.bJS_progressbar'
          , slideSelector        : '.bJS_slider'
          , previewSlideSelector : '.bJS_previewSlider'
          , progressInterval     : ''
              // add your own progressbar animation function to sync it i.e. with a video
              // function will be called if the current preview slider item (".b_previewItem") has the data-customprogressbar="true" property set
          , onCustomProgressbar : function($slide, $progressbar) {}
      }

          // slick slider options
          // see: https://kenwheeler.github.io/slick/
      var sliderOptions = {
          slidesToShow   : 1,
          slidesToScroll : 1,
          arrows         : false,
          fade           : true,
          autoplay       : true
      }

          // slick slider options
          // see: https://kenwheeler.github.io/slick/
      var previewSliderOptions = {
          slidesToShow   : 1,
          slidesToScroll : 1,
          dots           : false,
          focusOnSelect  : true,
          centerMode     : true
      }
      }

  });