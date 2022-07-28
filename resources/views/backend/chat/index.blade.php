@extends('layouts.vertical', ['demo' => 'creative', 'title' => getNomenclatureName('Product Reviews', True)])
@section('css')
    <link href="{{ asset('assets/libs/dropzone/dropzone.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/dropify/dropify.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/mohithg-switchery/mohithg-switchery.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/multiselect/multiselect.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/select2/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/selectize/selectize.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/libs/bootstrap-selectroyoorders/bootstrap-select.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/libs/bootstrap-touchspin/bootstrap-touchspin.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/libs/nestable2/nestable2.min.css') }}" rel="stylesheet" type="text/css" />
    {{-- <style>
        .error {
            color: red;
        }
        .chat-list-item {
        cursor: pointer;
        padding: 14px 16px;
        }
        .mdi-circle:before {
        content: "󰝥";
        }
        .chat-box-wrapper {
            max-height: 538px;
            overflow-y: scroll;
        }
        .chat-input-section {
            background-color: #fff;
            display: block;
            z-index: 1;
            position: relative;
        }
        .mdi-set,
        .mdi:before {
        display: inline-block;
        font: normal normal normal 24px/1 Material Design Icons;
        font-size: inherit;
        text-rendering: auto;
        line-height: inherit;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        }


    </style> --}}
@endsection
@section('customcss')
<link href="{{ asset('assets/libs/chat/chat.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">{{ getNomenclatureName('Admin Chat', true) }}</h4>
                </div>
            </div>
        </div>

    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body position-relative">
                        <div class="container">
                            <div class="chat-body row overflow-hidden shadow bg-light rounded">

                                @include('backend.chat.part.left') 
                                @include('backend.chat.part.right') 
                            </div>
                        </div>
                    
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                }
            });
        });
    </script>
@endsection
@section('script-bottom')

<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script src="{{asset('assets/js/chat/chat.js')}}"></script>
<script src="{{asset('assets/js/chat/socket_chat.js')}}"></script>
{{-- <script src="{{asset('assets/js/chat/chatNotifications.js')}}"></script> --}}


<script>
    $(document).ready(async function(){

          // Create SocketIO instance, connect
    })
   
  </script>
@endsection
