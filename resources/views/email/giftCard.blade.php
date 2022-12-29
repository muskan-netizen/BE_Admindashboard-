<style>
    .box {
    position: relative;
    max-width: 450px;
    width: 90%;
    height: 262px;
    background: #fff;
    box-shadow: 0 0 15px rgba(0,0,0,.1);
    margin: 20px auto;
}

/* common */
.ribbon {
  width: 150px;
  height: 150px;
  overflow: hidden;
  position: absolute;
  z-index: 1;
}
.ribbon::before,
.ribbon::after {
  position: absolute;
  z-index: -1;
  content: '';
  display: block;
  
}
.ribbon span {
  position: absolute;
  display: block;
  width: 225px;
  padding: 15px 0;
  box-shadow: 0 5px 10px rgba(0,0,0,.1);
  color: #fff;
  font: 700 18px/1 'Lato', sans-serif;
  text-shadow: 0 1px 1px rgba(0,0,0,.2);
  text-transform: uppercase;
  text-align: center;
}

/* top left*/
.ribbon-top-left {
  top: -10px;
  left: -10px;
}
.ribbon-top-left::before,
.ribbon-top-left::after {
  border-top-color: transparent;
  border-left-color: transparent;
}
.ribbon-top-left::before {
  top: 0;
  right: 0;
}
.ribbon-top-left::after {
  bottom: 0;
  left: 0;
}
.ribbon-top-left span {
  right: -25px;
  top: 30px;
  transform: rotate(-45deg);
}


.gift-card__msg {
  font-size: 10px;
  display: block;
  margin-top: 10px;
}

.gift-card__details {
  margin-top: auto;
  align-items: center;
  line-height: 1;
}

.gift-card__code {
  display: inline-block;
  background: white;
  color: black;
  padding: 10px 13px;
  margin-top: 20px;
  font-size: 20px;
  border: 1px solid #e3e3e3;
}

.gift-card__amount {
  font-size: 70px;
}
.gift-card__amount-remaining {
  font-size: 14px;
  margin-top: 7px;
}
.gift-card__image {
  border-top-left-radius: 10px;
  border-bottom-left-radius: 10px;
  max-width: 150px;
  background-size: cover;
  background-image: url({{  $GiftCard->image['proxy_url'].'100/100'.$GiftCard->image['image_path'] }});
}
.green__ribbon span{
    background-color: #93C63C;
}
.green__ribbon:before,
.green__ribbon:after{
    border: 5px solid #4E7212;
}
.red__ribbon span{
    background-color: #EF1313;
}
.red__ribbon:before,
.red__ribbon:after{
    border: 5px solid #780C0C;
}
      </style>
<div class="wrapper ">
  
    <div class="box">
       <div class="ribbon ribbon-top-left green__ribbon"><span>{{ $GiftCard->title }}</span></div>
       <article class="gift-card row m-0">
          <div class="gift-card__image col-4 p-0">
          </div>
          <section class="gift-card__content col-8 text-right pt-4">
                <div class="gift-card__amount">{{$currSymbol. $GiftCard->amount }}</div>
                
                <div class="gift-card__code w-100 text-center bg-light">{{ $GiftCard->userCode }}</div>
                <div class="gift-card__msg mb-2 text-truncate">{{ $GiftCard->short_desc }}</div>
          </section>
          </article>
    </div>
 </div>