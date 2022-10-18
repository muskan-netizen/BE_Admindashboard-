
{{-- 
* it's use for get translation in js file
* @Author Mr amit mehra and Mr Harbans singh 
--}}
@php
$lang            = config('app.locale');
$langFile        = resource_path('lang/' . $lang . '.json');
$langFileString  = file_get_contents($langFile, $lang . '.json');
@endphp
@section('headerJs')
langTranslation.js
<script>
    var LangObjectJS = <?php  echo @$langFileString  ?>

    const _language = { 
        getLanString(str) {
            if(LangObjectJS[str] == undefined){
                return str;
            }
            return LangObjectJS[str];
        }
    }
</script>
@endsection
