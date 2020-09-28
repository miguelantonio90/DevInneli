<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>INNELI</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.css">
    <script src="//cdn.jsdelivr.net/chartist.js/latest/chartist.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment"></script>


    <!-- Styles -->
    {{--        <style type="text/css">--}}
    {{--            @font-face{--}}
    {{--                font-family: IRANSans;--}}
    {{--                src: url("{{ asset('fonts/iransans.ttf') }}")  format("truetype");--}}
    {{--            }--}}
    {{--            .application {--}}
    {{--                font-family: "IRANSans", serif;--}}
    {{--            }--}}
    {{--            .vpd-input-group {--}}
    {{--                width:10px!important;--}}
    {{--            }--}}
    {{--        </style>--}}

</head>
<body>
<div id="app">
    <app></app>
</div>
<script src="{{mix('js/main.js')}}"></script>
<script type="text/javascript">window.$crisp=[];window.CRISP_WEBSITE_ID="bba57f57-ec20-4ec3-843d-d0863c14bf15";(function(){d=document;s=d.createElement("script");s.src="https://client.crisp.chat/l.js";s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})();</script>
</body>
</html>
