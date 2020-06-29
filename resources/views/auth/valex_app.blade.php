<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @php $favicon = getSetting('favicon'); @endphp
    @if(isset($favicon) && $favicon!=''  && \Storage::exists(config('constants.SETTING_IMAGE_URL').$favicon))
    <link rel="shortcut icon" href="{{ \Storage::url(config('constants.SETTING_IMAGE_URL').$favicon) }}">
    @endif
    
    <script src="{{ asset('template/valex-theme/plugins/jquery/jquery.min.js') }}"></script>
    <link href="{{ asset('template/valex-theme/css/style.css') }}" rel="stylesheet">

    <link href="{{ asset('template/valex-theme/css/custom.css') }}" rel="stylesheet">
 
@yield('frontend_styles')
</head>
    <body class="main-body bg-primary-transparent">
        @yield('content')
    </body>

<script type="text/javascript">
$.ajaxSetup({

    headers: {

        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

    }

});



var formValidationOptions = {

    errorElement: 'strong', //default input error message container

    errorClass: 'help-block', // default input error message class

    focusInvalid: true, // do not focus the last invalid input

    ignore: "",

    errorPlacement: function (error, element) { // render error placement for each input type

        if (element.attr("data-error-container")) { 

            error.appendTo(element.attr("data-error-container"));

        }else{

            error.insertAfter(element); // for other inputs, just perform default behavior

        }

    },

    highlight: function (element) { // hightlight error inputs

        jQuery(element)

            .closest('.form-group').addClass('{{ config("constants.ERROR_FORM_GROUP_CLASS") }}').removeClass('has-success'); // set error class to the control group

    },

    unhighlight: function (element) { // revert the change done by hightlight

        jQuery(element)

            .closest('.form-group').removeClass('{{ config("constants.ERROR_FORM_GROUP_CLASS") }}'); // set error class to the control group

    },

    success: function (label) {

        label

        .closest('.form-group').removeClass('{{ config("constants.ERROR_FORM_GROUP_CLASS") }}'); // set success class to the control group

    }

};



jQuery('.autoFillOff').attr('readonly', true);

setTimeout(function(){

    jQuery('.autoFillOff').attr('readonly', false)

}, 1000);

jQuery(document).ready(function(){

    if(jQuery.validator)

        jQuery.validator.setDefaults(formValidationOptions);



    var url = window.location;

    var element = $('ul#accordionSidebar a').filter(function() {

        var href = this.href;

        var a = href.indexOf("?");

        var b =  href.substring(a);

        var c = href.replace(b,"");

        if(a == -1)

            href = b;

        else

            href = c;



        return href == url.href || url.href.indexOf(href) == 0;

    }).addClass('active').closest('li');



    if(element.is('li')){

      element.addClass('active');

      element.find('a').trigger('click');

    }

});

</script>
@yield('frontend_script')

</html>    