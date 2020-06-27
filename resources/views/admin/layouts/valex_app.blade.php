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

    <!-- Favicon -->

	<!-- Icons css -->

	<link href="{{asset('template/valex-theme/css/icons.css')}}" rel="stylesheet">

	<!--  Right-sidemenu css -->

	<link href="{{asset('template/valex-theme/plugins/sidebar/sidebar.css') }}" rel="stylesheet">

	<!--  Custom Scroll bar-->

	<link href="{{asset('template/valex-theme/plugins/mscrollbar/jquery.mCustomScrollbar.css') }}" rel="stylesheet"/>

	<!--- Style css-->

	<link href="{{ asset('template/valex-theme/css/style.css') }}" rel="stylesheet">

	<link href="{{ asset('template/valex-theme/css/style-dark.css') }}" rel="stylesheet">

	<!-- Morris.js Charts Plugin -->

	<!---Skinmodes css-->

	<link href="{{ asset('template/valex-theme/css/skin-modes.css') }}" rel="stylesheet" />

	<!--- Animations css-->

	<link href="{{ asset('template/valex-theme/css/animate.css') }}" rel="stylesheet">



    <link href="{{ asset('template/valex-theme/css/custom.css') }}" rel="stylesheet">

 @yield('styles')

</head>

<body class="main-body">
   
   @include('admin.layouts.top_header')

    <?php if(auth()->user()->id == config('constants.ROLE_TYPE_SUPERADMIN_ID')){ ?>
        @include('admin.layouts.admin_menu_header')
    <?php }else{ ?>
        @include('admin.layouts.menu_header')
    <?php } ?>
    <div class="main-content horizontal-content">
        <div class="container">
          @if($flash = (session('error') ?: session('danger')))            
              <div class="alert alert-danger alert-dismissible bg-danger-gradient" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   {{ $flash }}
              </div>
          @endif
          @if($flash = session('success'))      
              <div class="alert alert-success alert-dismissible bg-success-gradient" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                   {{ $flash }}
              </div>
          @endif
        </div>
       @yield('content')
    </div>

	<div class="main-footer ht-40">

		<div class="container-fluid pd-t-0-f ht-100p">

			<span> Copyright © 2020 <a href="{{ route('admin.dashboard') }}">{{ config('app.name', 'Laravel') }}</a>.All rights reserved.</span>

		</div>

	</div>

<!-- Footer closed -->				<!-- JQuery min js -->

<script src="{{ asset('template/valex-theme/plugins/jquery/jquery.min.js') }}"></script>

<!-- Bootstrap Bundle js -->

<script src="{{ asset('template/valex-theme/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- <script src="{{ asset('template/valex-theme/plugins/ionicons/ionicons.js') }}"></script> -->

<!--Internal  index js -->



<!-- Moment js -->

<script src="{{ asset('template/valex-theme/plugins/moment/moment.js') }}"></script>



<script src="{{ asset('template/valex-theme/plugins/jquery-sparkline/jquery.sparkline.min.js')}}"></script>

<!-- Sticky js -->

<script src="{{ asset('template/valex-theme/js/sticky.js') }}"></script>



<script src="{{ asset('template/valex-theme/plugins/rating/jquery.rating-stars.js') }}"></script>

<script src="{{ asset('template/valex-theme/plugins/rating/jquery.barrating.js') }}"></script>



<!-- <script src="{{ asset('template/valex-theme/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>

<script src="{{ asset('template/valex-theme/plugins/perfect-scrollbar/p-scroll.js') }}"></script> -->

<!-- Horizontalmenu js-->

<!-- <script src="{{ asset('template/valex-theme/plugins/sidebar/sidebar.js') }}"></script>

<script src="{{ asset('template/valex-theme/plugins/sidebar/sidebar-custom.js') }}"></script> -->

<!-- Eva-icons js -->

<script src="{{ asset('template/valex-theme/js/eva-icons.min.js') }}"></script>

<!-- Horizontalmenu js-->

<script src="{{ asset('template/valex-theme/plugins/horizontal-menu/horizontal-menu-2/horizontal-menu.js') }}"></script>

<!-- custom js -->

<script src="{{ asset('template/valex-theme/js/custom.js') }}"></script>

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

@yield('scripts')

</body>

</html>