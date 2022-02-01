<script>
    $('.openAddModal').click(function() {
        $('#add-form').modal({
            //backdrop: 'static',
            keyboard: false
        });
        //runPicker();
        $('.dropify').dropify();
        $('.selectize-select').selectize();
        autocompletesWraps.push('add');
        loadMap(autocompletesWraps);
    });

    $('.openImportModal').click(function() {
        $('#import-form').modal({
            //backdrop: 'static',
            keyboard: false
        });
        //runPicker();
        $('.dropify').dropify();
    });

    function runPicker() {
        $('.datetime-datepicker').flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i"
        });

        $('.selectpicker').selectpicker();
    }

    var autocomplete = {};
    var autocompletesWraps = [];
    var count = 1;
    editCount = 0;
    $(document).ready(function() {

        autocompletesWraps.push('def');
        loadMap(autocompletesWraps);
    });

    function loadMap(autocompletesWraps) {

        // console.log(autocompletesWraps);
        $.each(autocompletesWraps, function(index, name) {
            const geocoder = new google.maps.Geocoder;

            if ($('#' + name).length == 0) {
                return;
            }
            //autocomplete[name] = new google.maps.places.Autocomplete(('.form-control')[0], { types: ['geocode'] }); console.log('hello');
            autocomplete[name] = new google.maps.places.Autocomplete(document.getElementById(name + "-address"), {
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
                        document.getElementById(name + '_latitude').value = lat;
                        document.getElementById(name + '_longitude').value = lng;
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
        console.log(no);

        var lats = document.getElementById(no + '_latitude').value;
        var lngs = document.getElementById(no + '_longitude').value;
        console.log(lats + '--' + lngs);

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
        // marker drag event
        google.maps.event.addListener(marker, 'drag', function(event) {
            document.getElementById('lat_map').value = event.latLng.lat();
            document.getElementById('lng_map').value = event.latLng.lng();
        });

        //marker drag event end
        google.maps.event.addListener(marker, 'dragend', function(event) {
            var zx = JSON.stringify(event);
            console.log(zx);


            document.getElementById('lat_map').value = event.latLng.lat();
            document.getElementById('lng_map').value = event.latLng.lng();
            //alert("lat=>"+event.latLng.lat());
            //alert("long=>"+event.latLng.lng());
        });
        $('#add-customer-modal').addClass('fadeIn');
        $('#show-map-modal').modal({
            //backdrop: 'static',
            keyboard: false
        });

    });

    $(document).on('click', '.selectMapLocation', function() {

        var mapLat = document.getElementById('lat_map').value;
        var mapLlng = document.getElementById('lng_map').value;
        var mapFor = document.getElementById('map_for').value;

        document.getElementById(mapFor + '_latitude').value = mapLat;
        document.getElementById(mapFor + '_longitude').value = mapLlng;

        $('#show-map-modal').modal('hide');
    });

    $(".openEditModal").click(function(e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
        var uri = "{{ isset($vendor) ? route('vendor.edit', $vendor->id) : '' }}";
        $.ajax({
            type: "get",
            url: uri,
            data: '',
            dataType: 'json',
            success: function(data) {
                $('#edit-form').modal('show');
                $('#edit-form #editCardBox').html(data.html);
                $('.selectize-select').selectize();
                $('.dropify').dropify();
                dine = document.getElementsByClassName('dine_in');
                var switchery = new Switchery(dine[0]);
                take = document.getElementsByClassName('takeaway');
                var switchery = new Switchery(take[0]);
                delivery = document.getElementsByClassName('delivery');
                var switchery = new Switchery(delivery[0]);
                autocompletesWraps.push('edit');
                loadMap(autocompletesWraps);
                // },
                // error: function (data) {
                //     console.log('data2');
                // },
                // beforeSend: function(){
                //     $(".loader_box").show();
                // },
                // complete: function(){
                //     $(".loader_box").hide();
            }
        });
    });

    function submitProductImportForm() {
        var form = document.getElementById('save_imported_products');
        var formData = new FormData(form);
        var data_uri = "{{route('product.import')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            headers: {
                Accept: "application/json"
            },
            url: data_uri,
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                location.reload();
                if (response.status == 'success') {
                    $(".modal .close").click();
                    location.reload();
                } else {

                    $(".show_all_error.invalid-feedback").show();
                    $(".show_all_error.invalid-feedback").text(response.message);
                }
                return response;
            },
            beforeSend: function() {

                $(".loader_box").show();
            },
            complete: function() {

                $(".loader_box").hide();
            }
        });
    }


    function submitImportForm() {
        var form = document.getElementById('save_imported_vendors');
        var formData = new FormData(form);
        var data_uri = "{{route('vendor.import')}}";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: "post",
            headers: {
                Accept: "application/json"
            },
            url: data_uri,
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                location.reload();
                if (response.status == 'success') {
                    // $("#import-form").modal('hide');
                    $('#p-message').empty();
                    $('#p-message').append('Document uploaded Successfully!');
                    setTimeout(function() {
                        location.reload();
                    }, 2000);

                } else {
                    $('#p-message').empty();
                    $('#p-message').append('Document uploading Failed!');
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                    $(".show_all_error.invalid-feedback").show();
                    $(".show_all_error.invalid-feedback").text(response.message);

                }
                return response;
            },
            beforeSend: function() {
                $('#p-message').empty();
                $('#p-message').append('Document uploading!');

                setTimeout(function() {
                    location.reload();
                }, 2000);

                $(".loader_box").show();
            },
            complete: function() {
                $('#p-message').empty();
                $('#p-message').append('Document uploading!');
                setTimeout(function() {
                    location.reload();
                }, 2000);


                $(".loader_box").hide();
            }
        });
    }

    $(document).on('click', '.submitAddForm', function(e) {
        e.preventDefault();
        var form = document.getElementById('save_banner_form');
        var formData = new FormData(form);
        var url = "{{route('vendor.store')}}";
        saveData(formData, 'add', url);

    });

    $(document).on('click', '.submitEditForm', function(e) {
        e.preventDefault();
        var form = document.getElementById('save_edit_banner_form');
        var formData = new FormData(form);
        var url = "{{ isset($vendor) ? route('vendor.update', $vendor->id) : ''}}";

        saveData(formData, 'edit', url);

    });

    function saveData(formData, type, data_uri) {
        console.log(data_uri);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: "post",
            headers: {
                Accept: "application/json"
            },
            url: data_uri,
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {

                if (response.status == 'success') {
                    $(".modal .close").click();
                    location.reload();
                } else {
                    $(".show_all_error.invalid-feedback").show();
                    $(".show_all_error.invalid-feedback").text(response.message);
                }
                return response;
            },
            beforeSend: function() {
                $(".loader_box").show();
            },
            complete: function() {
                $(".loader_box").hide();
            },
            error: function(response) {
                if (response.status === 422) {
                    let errors = response.responseJSON.errors;
                    Object.keys(errors).forEach(function(key) {
                        $("#" + key + "Input input").addClass("is-invalid");
                        $("#" + key + "Input span.invalid-feedback").children("strong").text(errors[key][0]);
                        $("#" + key + "Input span.invalid-feedback").show();
                    });
                } else {
                    $(".show_all_error.invalid-feedback").show();
                    $(".show_all_error.invalid-feedback").text('Something went wrong, Please try Again.');
                }
                return response;
            }
        });
    }
    $(".openAddonModal").click(function(e) {
        $('#addAddonmodal').modal({
            backdrop: 'static',
            keyboard: false
        });
        var slider = $("#slider-range").data("ionRangeSlider");
        var from = slider.result.from;
        var to = $('#banner-datatable >tbody >tr.input_tr').length;
        slider.update({
            grid: false,
        });
    });
    $(document).on('click', '.addOptionRow-Add', function(e) {
        var $tr = $('.optionTableAdd tbody>tr:first').next('tr');
        var $clone = $tr.clone();
        $clone.find(':text').val('');
        $clone.find('.lasttd').html('<a href="javascript:void(0);" class="action-icon deleteCurRow"> <i class="mdi mdi-delete"></i></a>');
        $('.optionTableAdd').append($clone);
        var slider = $("#slider-range").data("ionRangeSlider");
        var from = slider.result.from;
        var to = $('#banner-datatable >tbody >tr.input_tr').length;
        slider.update({
            min: from,
            max: to,
        });
    });

    $(document).on('click', '.addOptionRow-edit', function(e) {
        var $tr = $('.optionTableEdit tbody>tr:first').next('tr');
        var $clone = $tr.clone();
        $clone.find(':text').val('');
        $clone.find(':hidden').val('');
        $clone.find('.lasttd').html('<a href="javascript:void(0);" class="action-icon deleteCurRow"> <i class="mdi mdi-delete"></i></a>');
        $('.optionTableEdit').append($clone);
        var slider = $("#slider-range1").data("ionRangeSlider");
        var from = slider.result.from;
        var to = $('#edit_addon-datatable >tbody >tr.input_tr').length;
        slider.update({
            min: from,
            max: to,
        });
    });
    $("#addAddonmodal").on('click', '.deleteCurRow', function() {
        var slider = $("#addAddonmodal #slider-range").data("ionRangeSlider");
        var from = slider.result.from;
        var to = $('#addAddonmodal #banner-datatable >tbody >tr.input_tr').length - 1;
        slider.update({
            min: from,
            max: to,
        });
        $(this).closest('tr').remove();
        var slider = $("#slider-range").data("ionRangeSlider");
        var from = slider.result.from;
        var to = $('#banner-datatable >tbody >tr.input_tr').length;
        slider.update({
            min: from,
            max: to,
        });
    });

    $("#editdAddonmodal").on('click', '.deleteCurRow', function() {
        var slider = $("#editdAddonmodal #slider-range").data("ionRangeSlider");
        var from = slider.result.from;
        var to = $('#editdAddonmodal #edit_addon-datatable >tbody >tr.input_tr').length - 1;
        if (to == 1) {
            from = 0;
        }
        slider.update({
            min: from,
            max: to,
        });
        $(this).closest('tr').remove();
        var slider = $("#slider-range1").data("ionRangeSlider");
        var from = slider.result.from;
        var to = $('#edit_addon-datatable >tbody >tr.input_tr').length;
        slider.update({
            min: from,
            max: to,
        });
    });

    $(document).on('click', '.deleteAddon', function() {

        var did = $(this).attr('dataid');
        if (confirm("Are you sure? You want to delete this addon set.")) {
            $('#addonDeleteForm' + did).submit();
        }
        return false;
    });

    $('.editAddonBtn').on('click', function(e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
        var did = $(this).attr('dataid');
        $.ajax({
            type: "get",
            url: "<?php echo url('client/addon'); ?>" + '/' + did + '/edit',
            data: '',
            dataType: 'json',
            success: function(data) {
                $('#editdAddonmodal').modal({
                    backdrop: 'static',
                    keyboard: false
                });
                $('#editAddonForm #editAddonBox').html(data.html);
                $('#editdAddonmodal .modal-title').html('Edit AddOn Set');
                $('#editdAddonmodal .editAddonSubmit').html('Update');
                document.getElementById('editAddonForm').action = data.submitUrl;
                setTimeout(function() {
                    var max = $('#edit_addon-datatable >tbody >tr.input_tr').length;
                    var $d4 = $("#editAddonForm #slider-range1");
                    $d4.ionRangeSlider({
                        type: "double",
                        grid: false,
                        min: 0,
                        max: max,
                        from: data.min_select,
                        to: data.max_select
                    });
                    $d4.on("change", function() {
                        var $inp = $(this);
                        $("#editAddonForm #max_select").val($inp.data("to"));
                        $("#editAddonForm #min_select").val($inp.data("from"));
                    });
                }, 1000);
            },
            beforeSend: function() {
                $(".loader_box").show();
            },
            complete: function() {
                $(".loader_box").hide();
            },
            error: function(data) {
                console.log('data2');
            }
        });
    });
    // search users for set permission
    $('#search_user_for_vendor_permission').keyup(function(){
        var query = $(this).val();
        var vendor_id = 1;
        if(query != '')
        {
            var _token = $('input[name="_token"]').val();
            $.ajax({
            url:"{{ route('searchUserForPermission') }}",
            method:"POST",
            data:{query:query, _token:_token, vendor_id:vendor_id},
            success:function(data){
            $('#userList').fadeIn();
            $('#userList').html(data);
            }
            });
        }
    });
    $(document).on('click', '#userList li', function(){
        $('#search_user_for_vendor_permission').val($(this).text());
        $('#userId').val($(this).attr('data-id'));
        $('#userList').fadeOut();
    });

    // search users for set permission
    $('#search_user_for_permission').keyup(function(){
        var query = $(this).val();
        var vendor_id = 0;
        if(query != '')
        {
            var _token = $('input[name="_token"]').val();
            $.ajax({
            url:"{{ route('searchUserForPermission') }}",
            method:"POST",
            data:{query:query, _token:_token, vendor_id:vendor_id},
            success:function(data){
            $('#userList').fadeIn();
            $('#userList').html(data);
            }
            });
        }
    });

    ///// **************** 1.1  check vendor exists in dispatcher or not for pickup********** //////////

    $(".openConfirmDispatcher").click(function(e) {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        e.preventDefault();


        var uri = "{{route('update.Create.Vendor.In.Dispatch')}}";
        var id = $(this).data('id');

        $.ajax({
            type: "post",
            url: uri,
            data: {
                id: id
            },
            dataType: 'json',
            success: function(data) {
                var url = data.url;
                window.open(url, '_blank');
            },
            error: function(data) {
                Swal.fire({
                    // title: "Warning!",
                    text: data.message,
                    icon : "error",
                    button: "{{__('ok')}}",
                });
                //alert(data.message);
            },
            beforeSend: function() {
                $(".loader_box").show();
                var token = $('meta[name="csrf_token"]').attr('content');
                if (token) {
                    return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                }
            },
            complete: function() {
                $(".loader_box").hide();
            }
        });
    });
    /////////////// **************   end 1.1 *****************************///////////////

    ///// **************** 1.2  check vendor exists in dispatcher or not for on demand********** //////////

    $(".openConfirmDispatcherOnDemand").click(function(e) {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        e.preventDefault();


        var uri = "{{route('update.Create.Vendor.In.Dispatch.OnDemand')}}";
        var id = $(this).data('id');

        $.ajax({
            type: "post",
            url: uri,
            data: {
                id: id
            },
            dataType: 'json',
            success: function(data) {
                var url = data.url;
                window.open(url, '_blank');
            },
            error: function(data) {
                Swal.fire({
                    // title: "Warning!",
                    text: data.message,
                    icon : "error",
                    button: "{{__('ok')}}",
                });
                //alert(data.message);
            },
            beforeSend: function() {
                $(".loader_box").show();
                var token = $('meta[name="csrf_token"]').attr('content');
                if (token) {
                    return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                }
            },
            complete: function() {
                $(".loader_box").hide();
            }
        });
    });
    /////////////// **************   end 1.2 *****************************///////////////
    ///// **************** 1.3  check vendor exists in dispatcher or not for laundry********** //////////

    $(".openConfirmDispatcherLaundry").click(function(e) {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });

    e.preventDefault();


    var uri = "{{route('update.Create.Vendor.In.Dispatch.Laundry')}}";
    var id = $(this).data('id');

    $.ajax({
        type: "post",
        url: uri,
        data: {
            id: id
        },
        dataType: 'json',
        success: function(data) {
            var url = data.url;
            window.open(url, '_blank');
        },
        error: function(data) {
            alert(data.message);
        },
        beforeSend: function() {
            $(".loader_box").show();
            var token = $('meta[name="csrf_token"]').attr('content');
            if (token) {
                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
            }
        },
        complete: function() {
            $(".loader_box").hide();
        }
    });
});
/////////////// **************   end 1.3 *****************************///////////////
</script>
