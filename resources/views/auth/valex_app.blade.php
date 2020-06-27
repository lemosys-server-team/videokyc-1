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


    <link href="{{ asset('template/valex-theme/plugins/sidemenu-responsive-tabs/css/sidemenu-responsive-tabs.css') }}" rel="stylesheet">

    <!---Skinmodes css-->
    <!-- <link href="assets/css/skin-modes.css" rel="stylesheet" />
    <link href="assets/css/animate.css" rel="stylesheet"> -->



 @yield('styles')
</head>
    <body class="main-body bg-primary-transparent">
        @yield('content')
    </body>
    <!-- Footer closed -->              <!-- JQuery min js -->
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


<script src="{{ asset('template/valex-theme/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('template/valex-theme/plugins/perfect-scrollbar/p-scroll.js') }}"></script>

<script src="{{ asset('template/valex-theme/js/eva-icons.min.js') }}"></script>
<!-- Horizontalmenu js-->
<script src="{{ asset('template/valex-theme/plugins/horizontal-menu/horizontal-menu-2/horizontal-menu.js') }}"></script>
<!-- custom js -->
<script src="{{ asset('template/valex-theme/js/custom.js') }}"></script>
<script src="{{ asset('template/valex-theme/plugins/sidebar/sidebar.js') }}"></script>
<script src="{{ asset('template/valex-theme/plugins/sidebar/sidebar-custom.js') }}"></script>
</body>
</html>    