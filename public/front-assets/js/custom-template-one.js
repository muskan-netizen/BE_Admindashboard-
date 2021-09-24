jQuery('.home-banner-slider').slick({
    infinite: true,
    slidesToShow: 1,
    slidesToScroll: 1,
    responsive: [
        {
          breakpoint: 1200,
          settings: {
            arrows: false,
            dots: false
          }
        }
    ]
});
      

$('.newly-arrived-slider').slick({
    dots: true,
    infinite: true,
    speed: 300,
    slidesToShow: 6,
    slidesToScroll: 2,
    arrows: false,
    dots: false,
    responsive: [
      {
        breakpoint: 1200,
        settings: {
          slidesToShow: 4,
          slidesToScroll: 1,
          infinite: true,
          dots: false
        }
      },
      {
        breakpoint: 767,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1
        }
      }
      // You can unslick at a given breakpoint now by adding:
      // settings: "unslick"
      // instead of a settings object
    ]
});

// $('.brand-slider').slick({
//     dots: true,
//     infinite: true,
//     arrows:false,
//     speed: 300,
//     slidesToShow: 4,
//     slidesToScroll: 1,
//     arrows: false,
//     responsive: [
//       {
//         breakpoint: 1367,
//         settings: {
//           slidesToShow: 3,
//           slidesToScroll: 1,
//           arrows: false,
//           infinite: true
//         }
//       },
//       {
//         breakpoint: 991,
//         settings: {
//           slidesToShow: 2,
//           arrows: false,
//           slidesToScroll: 1 
//         }
//       },
//       {
//         breakpoint: 767,
//         settings: {
//           slidesToShow: 2,
//           arrows: false,
//           slidesToScroll: 1
//         }
//       },
//       {
//         breakpoint: 480,
//         settings: {
//           slidesToShow: 1,
//           arrows:false,
//           slidesToScroll: 1
//         }
//       }
//     ]
// });

// $('.suppliers-slider').slick({
//     dots: false,
//     infinite: true,
//     speed: 300,
//     slidesToShow: 4,
//     slidesToScroll: 1,
//     arrows: false,
//     dots: false,
//     responsive: [
//       {
//         breakpoint: 991,
//         settings: {
//           slidesToShow: 3,
//           slidesToScroll: 1,
//           infinite: true,
//           dots: false
//         }
//       },
//       {
//         breakpoint: 767,
//         settings: {
//           slidesToShow: 2,
//           slidesToScroll: 1,
//           dots: false
//         }
//       },
//       {
//         breakpoint: 480,
//         settings: {
//           slidesToShow: 1,
//           slidesToScroll: 1,
//           dots: false
//         }
//       }
//       // You can unslick at a given breakpoint now by adding:
//       // settings: "unslick"
//       // instead of a settings object
//     ]
// });