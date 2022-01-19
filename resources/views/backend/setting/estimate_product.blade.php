@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Estimate Products'])
@section('css')
@endsection
@section('content')



<div class="container-fluid custom-toggle">
   <div class="row">
      <div class="col-12">
         <div class="page-title-box">
            <h4 class="page-title text-uppercase">{{ __('Estimate Products') }}</h4>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-12">
         <div class="text-sm-left">
            @if (\Session::has('success'))
            <div class="alert alert-success">
               <span>{!! \Session::get('success') !!}</span>
            </div>
            @elseif(\Session::has('error'))
            <div class="alert alert-danger">
               <span>{!! \Session::get('error') !!}</span>
            </div>
            @endif
         </div>
      </div>
   </div>
  
   <div class="col-xl-6 mb-3">

      <form method="POST" class="h-100" action="{{route('referandearn.update', Auth::user()->code)}}">
         @csrf
         <div class="card-box product-tags mb-0 pb-1">
            <div class="d-flex align-items-center justify-content-between">
               <h4 class="header-title text-uppercase">{{ __('Product')}}</h4>
               <a class="btn btn-info d-block" id="add_product_tag_modal_btn">
                  <i class="mdi mdi-plus-circle mr-1"></i>{{ __("Add") }}
               </a>
            </div>
            <div class="table-responsive mt-3 mb-1">
               <table class="table table-centered table-nowrap table-striped" id="promo-datatable">
                  <thead>
                     <tr>
                        <th>{{ __("Icon") }}</th>
                        <th>{{ __("Name") }}</th>
                        <th>{{ __("Action") }}</th>
                     </tr>
                  </thead>
                  <tbody id="post_list">
                     @forelse($estimate_products as $tag)
                        <tr>
                           <td>
                              @if(isset($tag->icon) && !empty($tag->icon)) <img src="{{ $tag->icon['proxy_url'].'100/100'.$tag->icon['image_path'] }}">@endif
                           </td>
                           <td>
                              <a class="edit_product_tag_btn" data-estimate_product_id="{{$tag->id}}" href="javascript:void(0)">
                                 {{$tag->primary ? $tag->primary->name : ''}}
                              </a>
                           </td>
                           <td>
                              <div>
                                 <div class="inner-div" style="float: left;">
                                    <a class="action-icon edit_product_tag_btn" data-estimate_product_id="{{$tag->id}}" href="javascript:void(0)">
                                       <i class="mdi mdi-square-edit-outline"></i>
                                    </a>
                                 </div>
                                 <div class="inner-div">
                                    <button type="button" class="btn btn-primary-outline action-icon delete_product_tag_btn" data-estimate_product_id="{{$tag->id}}">
                                       <i class="mdi mdi-delete"></i>
                                    </button>
                                 </div>
                              </div>
                           </td>
                        </tr>
                     @empty
                        <tr align="center">
                           <td colspan="4" style="padding: 20px 0">{{ __("Products not found.") }}</td>
                        </tr>
                     @endforelse
                  </tbody>
               </table>
            </div>
         </div>
      </form>
   </div>


   <!-- modal for product tags -->
   <div id="add_product_tag_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="standard-modalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
         <div class="modal-content">
            <div class="modal-header border-bottom">
               <h4 class="modal-title" id="standard-modalLabel">{{ __("Add Product") }}</h4>
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
               <form id="productTagForm" method="POST" action="javascript:void(0)" enctype="multipart/form-data">
                  @csrf
                  <div id="save_product_tag">
                     <input type="hidden" name="estimate_product_id" value="">
                     <div class="row">
                        <div class="col-md-3">
                           <label>{{ __('Upload Icon') }}</label>
                           <input type="file" accept="image/*" data-plugins="dropify" name="icon" class="dropify"  />
                           <label class="logo-size text-right w-100">{{ __("Icon Size") }} 100X100</label>
                       </div>

                        @forelse($client_languages as $k => $client_language)
                        <div class="col-md-6 mb-2">
                           <div class="row">
                              <div class="col-12">
                                 <div class="form-group position-relative">
                                    <label for="">{{ __("Name") }} ({{$client_language->langName}})</label>
                                    <input class="form-control" name="language_id[{{$k}}]" type="hidden" value="{{$client_language->langId}}">
                                    <input class="form-control" name="name[{{$k}}]" type="text" id="product_tag_name_{{$client_language->langId}}">
                                 </div>
                                 @if($k == 0)
                                    <span class="text-danger error-text product_tag_err"></span>
                                 @endif
                              </div>
                           </div>
                        </div>
                        @empty
                        @endforelse
                     </div>
                  </div>
               </form>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-primary submitSaveProductTag">{{ __("Save") }}</button>
            </div>
         </div>
      </div>
   </div>
   

   @endsection
   @section('script')
   <script type="text/javascript">
     
     $('#add_product_tag_modal_btn').click(function(e) {
         document.getElementById("productTagForm").reset();
         $('#add_product_tag_modal input[name=estimate_product]').val("");
         $('#add_product_tag_modal').modal('show');
         $('#add_product_tag__modal #standard-modalLabel').html('Add Tag');
      });

    $(document).on("change", "#file_type_select", function() {
        var file_type = $(this).val();
        if(file_type == 'selector'){
            $("#selector_div").removeClass("d-none");
            var classoption_section = $('#option_div').find('.option_section');
            if(classoption_section.length==0){
                addoptionTemplate(0);
            }
        }
        else{
            $("#selector_div").addClass("d-none");
        }
    });
    $(document).on('click','.add_more_button',function(){
        var main_id = $(this).data('id');
        addoptionTemplate(main_id);
        console.log($('.add_more_button').length);
    });
    $(document).on('click','.remove_more_button',function(){
        var main_id =$(this).data('id');
        removeSeletOptionSectionTemplate(main_id);
        $('.add_more_button').each(function(key,value){
            if(key == ($('.add_more_button').length-1)){
                $('#add_button_'+$(this).data('id')).show();
            }
        });
    });
    $(document).on("change","#option_client_language",function() {
        let vendor_registration_document_id = $('input[name="vendor_registration_document_id"]').val();
        editVendorRegistrationForm(vendor_registration_document_id);
    });
    function removeSeletOptionSectionTemplate(div_id){
        $('#option_section_'+div_id).remove();
    }
    $(document).on('click', '.addOptionRow-Add', function(e) {
        var d = new Date();
        var n = d.getTime();
        var $tr = $('.optionTableAdd tbody>tr:first').next('tr');
        var $clone = $tr.clone();
        $clone.find(':text').val('');
        $clone.find('.hexa-colorpicker').attr("id", "hexa-colorpicker-" + n);
        $clone.find('.lasttd').html('<a href="javascript:void(0);" class="action-icon deleteCurRow"> <i class="mdi mdi-delete"></i></a>');
        $('.optionTableAdd').append($clone);

    });

    function addoptionTemplate(section_id){
        section_id                = parseInt(section_id);
        section_id                = section_id +1;
        var data                  = '';

        var price_section_temp    = $('#vendorSelectorTemp').html();
        var modified_temp         = _.template(price_section_temp);
        var result_html           = modified_temp({id:section_id,data:data});
        $("#table_body").append(result_html);
        $('.add_more_button').hide();
        $('#add_button_'+section_id).show();
    }
      $('#add_slot_modal_btn').click(function(e) {
         document.getElementById("slotForm").reset();
         $('#add_slot_modal input[name=slot_id]').val("");
         $('#add_slot_modal').modal('show');
         $('#add_slot__modal #standard-modalLabel').html('Add Slot');
      });

    

      ///   product tag ////
      $(document).on("click", ".delete_product_tag_btn", function() {
         var estimate_product_id = $(this).data('estimate_product_id');
         Swal.fire({
            title: "{{__('Are you Sure?')}}",
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Ok',
          }).then((result) => {
            if(result.value)
            {
               $.ajax({
                  type: "POST",
                  dataType: 'json',
                  url: "{{ route('estimations.delete') }}",
                  data: {
                     _token: "{{ csrf_token() }}",
                     estimate_product_id: estimate_product_id
                  },
                  success: function(response) {
                     if (response.status == "Success") {
                        $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                        setTimeout(function() {
                           location.reload()
                        }, 2000);
                     }
                  }
               });
            }
         });
      });
      $(document).on('click', '.submitSaveProductTag', function(e) {
         var estimate_product_id = $("#add_product_tag_modal input[name=estimate_product_id]").val();
         if (estimate_product_id) {
            var post_url = "{{ route('estimations.update') }}";
         } else {
            var post_url = "{{ route('estimations.create') }}";
         }
         var form_data = new FormData(document.getElementById("productTagForm"));
         $.ajax({
            url: post_url,
            method: 'POST',
            data: form_data,
            contentType: false,
            processData: false,
            success: function(response) {
               if (response.status == 'Success') {
                  $('#add_or_edit_social_media_modal').modal('hide');
                  $.NotificationApp.send("Success", response.message, "top-right", "#5ba035", "success");
                  setTimeout(function() {
                     location.reload()
                  }, 2000);
               } else {
                  $.NotificationApp.send("Error", response.message, "top-right", "#ab0535", "error");
               }
            },
            error: function(response) {
               $('#add_product_tag_modal .product_tag_err').html('The default language name field is required.');
            }
         });
      });
      $(document).on("click", ".edit_product_tag_btn", function() {
         let estimate_product_id = $(this).data('estimate_product_id');
         $('#add_product_tag_modal input[name=estimate_product_id]').val(estimate_product_id);
         $.ajax({
            method: 'GET',
            data: {
               estimate_product_id: estimate_product_id
            },
            url: "{{ route('estimations.edit') }}",
            success: function(response) {
               if (response.status = 'Success') {
                  $("#add_product_tag_modal input[name=estimate_product_id]").val(response.data.id);
                  $('#add_product_tag_modal #standard-modalLabel').html('Update Product Tag');
                  $('#add_product_tag_modal').modal('show');
                  $.each(response.data.translations, function( index, value ) {
                    $('#add_product_tag_modal #product_tag_name_'+value.language_id).val(value.name);
                  });
               }
            },
            error: function() {

            }
         });
      });
      // end product tag ////

      function generateRandomString(length) {
         var text = "";
         var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
         for (var i = 0; i < length; i++)
            text += possible.charAt(Math.floor(Math.random() * possible.length));
         return text;
      }

      function genrateKeyAndToken() {
         var key = generateRandomString(30);
         var token = generateRandomString(60);
         $('#personal_access_token_v1').val(key);
         $('#personal_access_token_v2').val(token);
      }
      var autocomplete = {};
      var autocompletesWraps = [];
      var count = 1;
      editCount = 0;
      $(document).ready(function() {
         autocompletesWraps.push('Default_location_name');
         loadMap(autocompletesWraps);
      });

      function loadMap(autocompletesWraps) {
         $.each(autocompletesWraps, function(index, name) {
            const geocoder = new google.maps.Geocoder;
            if ($('#' + name).length == 0) {
               return;
            }
            autocomplete[name] = new google.maps.places.Autocomplete(document.getElementById(name), {
               types: ['geocode']
            });
            google.maps.event.addListener(autocomplete[name], 'place_changed', function() {
               var place = autocomplete[name].getPlace();
               geocoder.geocode({
                  'placeId': place.place_id
               }, function(results, status) {
                  if (status === google.maps.GeocoderStatus.OK) {
                     const lat = results[0].geometry.location.lat();
                     const lng = results[0].geometry.location.lng();
                     document.getElementById('Default_latitude').value = lat;
                     document.getElementById('Default_longitude').value = lng;
                  }
               });
            });
         });

      }
      $('#show-map-modal').on('hide.bs.modal', function() {
         $('#add-customer-modal').removeClass('fadeIn');

      });

      $(document).on('click', '.showMap', function() {
         var no = $(this).attr('num');
         var lats = document.getElementById('Default_latitude').value;
         var lngs = document.getElementById('Default_longitude').value;

         document.getElementById('map_for').value = no;

         if (lats == null || lats == '0') {
            lats = 30.53899440;
         }
         if (lngs == null || lngs == '0') {
            lngs = 75.95503290;
         }

         var myLatlng = new google.maps.LatLng(lats, lngs);
         var mapProp = {
            center: myLatlng,
            zoom: 13,
            mapTypeId: google.maps.MapTypeId.ROADMAP

         };
         var map = new google.maps.Map(document.getElementById("googleMap"), mapProp);
         var marker = new google.maps.Marker({
            position: myLatlng,
            map: map,
            title: 'Hello World!',
            draggable: true
         });
         document.getElementById('lat_map').value = lats;
         document.getElementById('lng_map').value = lngs;
         google.maps.event.addListener(marker, 'drag', function(event) {
            document.getElementById('lat_map').value = event.latLng.lat();
            document.getElementById('lng_map').value = event.latLng.lng();
         });

         google.maps.event.addListener(marker, 'dragend', function(event) {
            var zx = JSON.stringify(event);
            console.log(zx);


            document.getElementById('lat_map').value = event.latLng.lat();
            document.getElementById('lng_map').value = event.latLng.lng();
         });
         $('#add-customer-modal').addClass('fadeIn');
         $('#show-map-modal').modal({
            keyboard: false
         });

      });

      $(document).on('click', '.selectMapLocation', function() {

         var mapLat = document.getElementById('lat_map').value;
         var mapLlng = document.getElementById('lng_map').value;
         var mapFor = document.getElementById('map_for').value;

         document.getElementById('Default_latitude').value = mapLat;
         document.getElementById('Default_longitude').value = mapLlng;

         $('#show-map-modal').modal('hide');
      });


      var hyprlocal = $('#is_hyperlocal');
      if(hyprlocal.length > 0){
         hyprlocal[0].onchange = function() {

         if ($('#is_hyperlocal:checked').length != 1) {
            $('.disableHyperLocal').hide();
         } else {
            $('.disableHyperLocal').show();
         }
         }
      }

      var delivery_service = $('#need_delivery_service');
      var dispatcherDiv = $('#need_dispacher_ride');
      var need_dispacher_home_other_service = $('#need_dispacher_home_other_service');
      var laundry_service = $('#need_laundry_service');

      if(delivery_service.length > 0){
         delivery_service[0].onchange = function() {

            if ($('#need_delivery_service:checked').length != 1) {
               $('.deliveryServiceFields').hide();
            } else {
               $('.deliveryServiceFields').show();
            }
         }
      }

      if(laundry_service.length > 0){
         laundry_service[0].onchange = function() {

            if ($('#need_laundry_service:checked').length != 1) {
               $('.laundryServiceFields').hide();
            } else {
               $('.laundryServiceFields').show();
            }
         }
      }

      if(dispatcherDiv.length > 0){
         dispatcherDiv[0].onchange = function() {
            console.log('ok');
            if ($('#need_dispacher_ride:checked').length != 1) {
               $('.dispatcherFields').hide();
            } else {
               $('.dispatcherFields').show();
            }
         }
      }

      if(need_dispacher_home_other_service.length > 0){
         need_dispacher_home_other_service[0].onchange = function() {

         if ($('#need_dispacher_home_other_service:checked').length != 1) {
            $('.home_other_dispatcherFields').hide();
         } else {
            $('.home_other_dispatcherFields').show();
         }
         }
      }


      var fb_login = $('#fb_login');

      fb_login[0].onchange = function() {
         if ($('#fb_login:checked').length != 1) {
            $('.fb_row').hide();
         } else {
            $('.fb_row').show();
         }
      }

      var twitter_login = $('#twitter_login');

      twitter_login[0].onchange = function() {
         if ($('#twitter_login:checked').length != 1) {
            $('.twitter_row').hide();
         } else {
            $('.twitter_row').show();
         }
      }

      var google_login = $('#google_login');

      google_login[0].onchange = function() {
         if ($('#google_login:checked').length != 1) {
            $('.google_row').hide();
         } else {
            $('.google_row').show();
         }
      }

      var apple_login = $('#apple_login');

      apple_login[0].onchange = function() {

         if ($('#apple_login:checked').length != 1) {
            $('.apple_row').hide();
         } else {
            $('.apple_row').show();
         }
      }

      var dinein_option = $('#dinein_check');
      if(dinein_option.length > 0){
         dinein_option[0].onchange = function() {
         optionsChecked("dinein_check");
         }
      }

      var takeaway_option = $('#takeaway_check');
      if(takeaway_option.length > 0){
         takeaway_option[0].onchange = function() {
         optionsChecked("takeaway_check");
      }
      }

      var delivery_option = $('#delivery_check');
      if(delivery_option > 0){
         delivery_option[0].onchange = function() {
         optionsChecked("delivery_check");
         }
      }


      function optionsChecked(id) {
         var delivery_checked = $("#delivery_check").is(":checked");
         var takeaway_checked = $("#takeaway_check").is(":checked");
         var dinein_checked = $("#dinein_check").is(":checked");
         if (dinein_checked == false && takeaway_checked == false && delivery_checked == false) {
            Swal.fire({
               title: "Warning!",
               text: "One option must be enables",
               icon: "warning",
               button: "OK",
            });
            $("#" + id).trigger('click');
         }
      }

      function toggle_smsFields(obj)
      {
         var id = $(obj).find(':selected').attr('data-id');
         $('.sms_fields').css('display','none');
         $('#'+id).css('display','flex');
         console.log(id);
      }
   </script>
   @endsection
