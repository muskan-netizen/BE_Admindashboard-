<link href="https://releases.transloadit.com/uppy/v3.7.0/uppy.min.css" rel="stylesheet">
<style>
.uppy-Dashboard-AddFiles-info{
    display: none !important;
}
.mediaSidenav {
  height:100vw;
  width: 0;
  position: fixed;
  z-index:9999;
  top: 0;
  right:0px;
  overflow-x: hidden;
  transition: 0.5s;
  padding-top: 10px;
}

.mediaSidenav a {
  padding: 8px 8px 8px 32px;
  text-decoration: none;
  font-size: 25px;
  color: #818181;
  display: block;
  transition: 0.3s;
}
.chat-input-section i {
    font-size: 18px;
}

.mediaSidenav a:hover {
  color: #f1f1f1;
}

.mediaSidenav .closebtn {
	position: absolute;
    top: 0;
    left: 0px;
    font-size: 24px;
    z-index: 2000;
    padding: 2px 4px;
    background-color:#43bee1;
    line-height: 20px;
    border-radius: 5px;color:#fff;
}

body {
  transition: margin-right .5s;
  padding: 16px;
}

@media screen and (max-height: 450px) {
  .mediaSidenav {padding-top: 15px;}
  .mediaSidenav a {font-size: 18px;}
}
/* .push_to_side{
	margin-right: 250px !important;
} */
/* .push_to_side #wrapper{
	margin-right: 250px;
} */

.push_to_side #mediaSidenav{
	width: 320px;
	margin-top:80px;
}
</style>


<div id="mediaSidenav" class="mediaSidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="openMediaNav()">&times;</a>
    <input  style="display: none;" type="file" id="uppy-select-files">
    <div id="uppy-progress"></div>
</div>

<script src="https://releases.transloadit.com/uppy/v3.7.0/uppy.min.js"></script>
<script src="{{asset('assets/js/chat/chatMedia.js')}}"></script>
<script>

function openMediaNav() {
	$('body').toggleClass('push_to_side');
    //   document.getElementById("mySidenav").style.width = "250px";
    //   document.getElementById("wrapper").style.marginRight = "250px";
    //   document.body.style.backgroundColor = "rgba(0,0,0,0.4)";
}
</script>