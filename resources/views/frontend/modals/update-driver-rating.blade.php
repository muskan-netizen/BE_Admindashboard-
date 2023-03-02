<style>
.rating_question_item .form-group {
    flex-direction: row-reverse;
    display: flex;
    width: 50px;
    justify-content: center;
    align-items: center;
}
.rating_question_item .form-group label {
    display: inline-block;
    text-transform: capitalize;
    font-weight: 600;
}

</style>



<h4>Rate your Driver</h4>
<form id="review-driver-form" class="theme-form" action="javascript:void(0)" method="post">
    @csrf
    <input type="hidden" name="order_vendor_product_id" value="{{$order_vendor_product_id}}">
    <input type="hidden" name="file_set" id="files_set" value="0">
    <input type="hidden" name="only_set_radio" id="only_set_radio" value="0">
    <input type="hidden" name="dispatch_traking_url" id="dispatch_traking_url" value="{{ $dispatch_traking_url }}">


    <textarea class="form-control" maxlength="500" name="hidden_review" hidden>{{$rating_details->review??''}}</textarea>
    @if(!empty($dispatch_rating_ques))
    <div id="rating_question" class="form-row">
     
            @foreach($dispatch_rating_ques as $question)
            <div class="col-md-12 mb-3 p-0" id="{{$question['id']}}Input">
                <div class="row w-100 align-items-center justify-content-between m-0 rating_question_item">
                    <label for=""><b>{{$question['title'] ? $question['title']  : ''}}</b></label>
                     <input type="hidden" name="question_id[]" value="{{ $question['id'] }}">
                    @if(($question['type']) == 5)
                        <div class="d-flex justify-space-between">
                            @foreach($question['option'] as $key => $option)
                            @php
                            $selected = (isset($question['order_quetions']) && !empty($question['order_quetions'])) ? $question['order_quetions']['option_id'] : '';
                            $checked = ($selected == $option['id']) ? 'checked': (($key ==0 ) ? 'checked' : '' );
                            @endphp
                            <div class="form-group ">
                                <input type="radio" id="option_{{ $option['id']}}" name="option_id_{{ $question['id'] }}" {{ $checked }} value="{{ $option['id'] }}">
                            <label for="option_{{ $option['id']}}">{{  $option['title'] }}</label>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            @endforeach
    </div>
    @endif
    @if(!empty($dispatch_rating_types))
    <div id="rating_types" class="form-row">
        <input type="hidden" name="rating_types" value='1'>
        @foreach($dispatch_rating_types as $rating_type)
        <div class="col-md-12 mb-3 p-0" id="{{$rating_type['id']}}Input">
            <div class="row w-100 align-items-center justify-content-between m-0 rating_question_item">
                <label for=""><b>{{$rating_type['title'] ? $rating_type['title']  : ''}}</b></label>
                 <input type="hidden" name="rating_type_id[]" value="{{ $rating_type['id'] }}">
                 @php
                 $dispatch_rating = (isset($rating_type['order_rating']) && !empty($rating_type['order_rating'])) ? $rating_type['order_rating']['rating'] : 0;
                 @endphp
                 @include('frontend.modals.driverRatingStar')
            </div>
        </div>
        @endforeach
    </div>
    @endif
    @if(empty($dispatch_rating_types))
    <div class="rating-form">
        <fieldset class="form-group">
            <legend class="form-legend">Rating:</legend>
            <div class="form-item">

            <input id="rating-5" name="rating" type="radio" value="5" {{ $rating == 5 ? 'checked' : '' }}/>
                <label for="rating-5" data-value="5">
                    <span class="rating-star">
                        <i class="fa fa-star-o"></i>
                        <i class="fa fa-star"></i>
                    </span>
                    <span class="ir">5</span>
                </label>
                <input id="rating-4" name="rating" type="radio" value="4"  {{ $rating == 4 ? 'checked' : '' }}/>
                <label for="rating-4" data-value="4">
                    <span class="rating-star">
                        <i class="fa fa-star-o"></i>
                        <i class="fa fa-star"></i>
                    </span>
                    <span class="ir">4</span>
                </label>
                <input id="rating-3" name="rating" type="radio" value="3"  {{ $rating == 3 ? 'checked' : '' }}/>
                <label for="rating-3" data-value="3">
                    <span class="rating-star">
                        <i class="fa fa-star-o"></i>
                        <i class="fa fa-star"></i>
                    </span>
                    <span class="ir">3</span>
                </label>
                <input id="rating-2" name="rating" type="radio" value="2"  {{ $rating == 2 ? 'checked' : '' }}/>
                <label for="rating-2" data-value="2">
                    <span class="rating-star">
                        <i class="fa fa-star-o"></i>
                        <i class="fa fa-star"></i>
                    </span>
                    <span class="ir">2</span>
                </label>
                <input id="rating-1" name="rating" type="radio" value="1"  {{ $rating == 1 ? 'checked' : '' }}/>
                <label for="rating-1" data-value="1">
                    <span class="rating-star">
                        <i class="fa fa-star-o"></i>
                        <i class="fa fa-star"></i>
                    </span>
                    <span class="ir">1</span>
                </label>

                <div class="form-output">
                    ? / 5
                </div>

            </div>
        </fieldset>
    </div>
    @endif
    

    <div class="form-row">

        <div class="col-md-12 mb-3">
            <label for="review">{{__('Review')}}</label>
            <textarea class="form-control"
                placeholder="{{__('Write Your Testimonial Here')}}"
                id="exampleFormControlTextarea1" rows="4"  name="review" maxlength="500">{{$rating_details->review??''}}</textarea>
        </div>
        <span class="text-danger" id="error-msg"></span>
        <span class="text-success" id="success-msg"></span>
        <div class="col-md-12">
            <button class="btn btn-solid buttonload" type="submit" id="review_agent_form_button">{{__('Submit Your Review')}}</button>
        </div>

    </div>
  </form>



<script type="text/javascript">
$(document).ready(function (e) {
    // $('body').delegate('.local-img-del','click',function() {
    //     var img_id = $(this).data('id');
    //     $(this).prev().remove();
    //     $(this).remove();
    //     $("#"+img_id).remove();
    // });


  $('input[type=radio][name=rating]').on('change', function() {
    $('#only_set_radio').val(1);
    $('.rating_files').show();
    $('.form-row').show();
    // $(this).closest("form").submit();
    });


    $('#review_agent_form_button').on('click',function(e){
        $('#only_set_radio').val(0);
        $(this).closest("form").submit();
    });


$.ajaxSetup({
headers: {
'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
}
});

$('#review-driver-form').submit(function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    var formdata = $('#review-driver-form').serialize();
    //alert(formdata);

    let review = $('#exampleFormControlTextarea1').val();
    $.ajax({
    type:'POST',
    url: "{{ route('update.driver.rating')}}",
    data: formdata,
    cache:false,
    // contentType: false,
    // processData: false,

    success: (data) => {
    if(data.status == 'Success')
        {   
            $('#success-msg').text(data.message);  
            var url = "{{route('user.orders',['pageType' => 'pastOrders'])}}";
            $(location).prop('href', url);           
        }else{
            $('#error-msg').text(data.message);
            $("#review_agent_form_button").html('Submit Your Review').prop('disabled', false);
        }
    },
    error: function(data){
        $('#error-msg').text(data.message);
        $("#review_agent_form_button").html('Submit Your Review').prop('disabled', false);
    }
});




});

});
</script>
