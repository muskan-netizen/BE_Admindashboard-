<!-- <style>
    body {
  background-color: #2F3242;
}
svg {
  position: absolute;
  top: 50%;
  left: 50%;
  margin-top: -250px;
  margin-left: -400px;
}
.message-box {
  height: 200px;
  width: 380px;
  position: absolute;
  top: 50%;
  left: 50%;
  margin-top: -100px;
  margin-left: 50px;
  color: #FFF;
  font-family: Roboto;
  font-weight: 300;
}
.message-box h1 {
  font-size: 60px;
  line-height: 46px;
  margin-bottom: 40px;
}
.buttons-con .action-link-wrap {
  margin-top: 40px;
}
.buttons-con .action-link-wrap a {
  background: #68c950;
  padding: 8px 25px;
  border-radius: 4px;
  color: #FFF;
  font-weight: bold;
  font-size: 14px;
  transition: all 0.3s linear;
  cursor: pointer;
  text-decoration: none;
  margin-right: 10px
}
.buttons-con .action-link-wrap a:hover {
  background: #5A5C6C;
  color: #fff;
}

#Polygon-1 , #Polygon-2 , #Polygon-3 , #Polygon-4 , #Polygon-4, #Polygon-5 {
  animation: float 1s infinite ease-in-out alternate;
}
#Polygon-2 {
  animation-delay: .2s;
}
#Polygon-3 {
  animation-delay: .4s;
}
#Polygon-4 {
  animation-delay: .6s;
}
#Polygon-5 {
  animation-delay: .8s;
}

@keyframes float {
	100% {
    transform: translateY(20px);
  }
}
@media (max-width: 450px) {
  svg {
    position: absolute;
    top: 50%;
    left: 50%;
    margin-top: -250px;
    margin-left: -190px;
  }
  .message-box {
    top: 50%;
    left: 50%;
    margin-top: -100px;
    margin-left: -190px;
    text-align: center;
  }
}
    </style> -->
    <!-- <svg width="380px" height="500px" viewBox="0 0 837 1045" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">
    <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">
        <path d="M353,9 L626.664028,170 L626.664028,487 L353,642 L79.3359724,487 L79.3359724,170 L353,9 Z" id="Polygon-1" stroke="#007FB2" stroke-width="6" sketch:type="MSShapeGroup"></path>
        <path d="M78.5,529 L147,569.186414 L147,648.311216 L78.5,687 L10,648.311216 L10,569.186414 L78.5,529 Z" id="Polygon-2" stroke="#EF4A5B" stroke-width="6" sketch:type="MSShapeGroup"></path>
        <path d="M773,186 L827,217.538705 L827,279.636651 L773,310 L719,279.636651 L719,217.538705 L773,186 Z" id="Polygon-3" stroke="#795D9C" stroke-width="6" sketch:type="MSShapeGroup"></path>
        <path d="M639,529 L773,607.846761 L773,763.091627 L639,839 L505,763.091627 L505,607.846761 L639,529 Z" id="Polygon-4" stroke="#F2773F" stroke-width="6" sketch:type="MSShapeGroup"></path>
        <path d="M281,801 L383,861.025276 L383,979.21169 L281,1037 L179,979.21169 L179,861.025276 L281,801 Z" id="Polygon-5" stroke="#36B455" stroke-width="6" sketch:type="MSShapeGroup"></path>
    </g>
</svg> -->
<!-- <div class="message-box">
  <h1>404</h1>
  <p>Page not found / Not available at your selected location</p>
  <div class="buttons-con">
    <div class="action-link-wrap">
      <a onclick="history.back(-1)" class="link-button link-back-button">Go Back</a>
      {{-- <a href="{{route('userHome')}}" class="link-button">Go to Home Page</a> --}}
    </div>
  </div>
</div> -->



<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style type="text/css">
:root {
  --color: 91 , 200, 109
}
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@700;800&display=swap');
body { font-family: 'Montserrat', sans-serif;margin: 0; overflow: hidden;
background-position: right !important;
background-repeat: no-repeat !important;
background-color: rgba({{getClientPreferenceDetail()->site_top_header_color}}, .1) !important;
 }


h1, h2, h3, h4, h5, h6 {
  font-family: "Montserrat", sans-serif; }
.loader-wrapper {
    height: 100vh;
    width: 100vw;
    display: flex;
    background-color: #ffffff;
    -webkit-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    -webkit-box-pack: center;
    -ms-flex-pack: center;
    justify-content: center;
    position: fixed;
    z-index: 9;
    top: 0;
}

.loader {
    border: 16px solid #111;
    border-radius: 50%;
    border-top: 16px double #5076db;
    border-bottom: 16px double #5076db;
    width: 80px;
    height: 80px;
    -webkit-animation: spin 2s linear infinite;
    animation: spin 2s linear infinite;
}
@-webkit-keyframes spin {
    0% {
        -webkit-transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(360deg);
    }
}
@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

.page-section .content-detail {
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}
.page-section .content-detail .sub-title,
.page-section .content-detail .detail-text {
    display: block;
    color: {{getClientPreferenceDetail()->web_color}};
    text-align: center;
}
.page-section .content-detail .sub-title {
    margin-bottom: 15px;
    font-weight: 600;
    font-size: 30px;
}
.page-section .content-detail .global-title {
    display: block;
    color: {{getClientPreferenceDetail()->site_top_header_color}};
    font-size: 150px;
    font-weight: 800;
    margin: 0;
}
/* .page-section .content-detail .global-title span {
    animation: animateblur 2s linear infinite;
} */
.page-section .content-detail .global-title span {
animation-name: animateblur;
animation-duration: 1.5s;
animation-timing-function:linear;
animation-fill-mode: forwards;
}
.page-section .content-detail .global-title span:nth-child(1) {
animation-delay: 1s; }
.page-section .content-detail .global-title span:nth-child(2) {
animation-delay: 2s; }
.page-section .content-detail .global-title span:nth-child(3) {
animation-delay: 3s; }

@keyframes animateblur {
    0% {
        opacity: 0;
        filter: blur(10px);
    }
    100% {
        opacity: 1;
        filter: blur(0px);
    }
}
.page-section .content-detail .back-btn {
    margin-top: 15px;
}
.page-section .content-detail .back-btn .btn {
    padding: 8px 15px;
    color: {{getClientPreferenceDetail()->web_color}};
    border: 1px solid {{getClientPreferenceDetail()->web_color}};
    border-radius: 30px;
    font-size: 14px;
    text-decoration: none;
}
.page-section .content-detail .back-btn .btn:hover {
    background-color: {{getClientPreferenceDetail()->web_color}};
    color: #fff;
}
.background_color {
    position: fixed;
    z-index: -1;
    height: 200%;
    width: 40%;
    top: 0;
    right: 0;
    transform: rotate(20deg);
    opacity: .3;
}

@media (max-width: 768px) {
    .page-section .content-detail {
        padding: 0 15px;
    }
    .page-section .content-detail .global-title {
        font-size: 120px;
        font-weight: 700;
    }
    .page-section .content-detail .sub-title {
        font-weight: 500;
        font-size: 25px;
    }
    .page-section .content-detail .detail-text {
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .page-section .content-detail {
        padding: 0 15px;
    }
    .page-section .content-detail .global-title {
        font-size: 80px;
        font-weight: 600;
    }
    .page-section .content-detail .sub-title {
        font-weight: 500;
        font-size: 22px;
    }
}
    </style>
 

</head>
<body>
    <div class="loader-wrapper" id="loader-wrapper" style="display: none;">
        <div class="loader"></div>
    </div>
    <section class="page-section">
        <div class="full-width-screen">
            <div class="container-fluid">
                <div class="content-detail">
                    <h1 class="global-title"><span>4</span><span>0</span><span>4</span></h1>

                    <h4 class="sub-title">Oops!</h4>

                    <p class="detail-text">We're sorry,<br> The page you were looking for doesn't exist anymore.</p>

                    <div class="back-btn">
                        <a href="{{route('userHome')}}" class="btn">Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="background_color"></div>
<script type="text/javascript" class="bg">
    var green = '{{getClientPreferenceDetail()->site_top_header_color}}';
    var red = '{{getClientPreferenceDetail()->site_top_header_color}}';
    var svg = '<svg width="393" height="393" viewBox="0 0 393 393" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="154.149" y="0.691137" width="283" height="283" rx="69.5" transform="rotate(32.7999 154.149 0.691137)" stroke="'+red+'"/><rect x="160.591" y="33.1011" width="236.275" height="236.275" rx="70" transform="rotate(32.7999 160.591 33.1011)" fill="'+green+'"/></svg>';
        var encoded = window.btoa(svg);
        document.getElementsByClassName("background_color")[0].style.background = "url(data:image/svg+xml;base64,"+encoded+")";
</script>


</body>
</html>
