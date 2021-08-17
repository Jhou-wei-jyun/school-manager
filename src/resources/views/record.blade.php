<!DOCTYPE html>
<html lang="zh-hant-TW" style="height:100%">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css" />
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.1.45/css/materialdesignicons.css">
    <title>Record</title>
    {{-- <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }
            .m-b-md {
                margin-bottom: 30px;
            }
        </style> --}}

</head>

<body style="background-color:#F0F0F0; height:110%">
    {{-- <div> --}}
    <div id="nav">
        <nav-component />
    </div>
    <div id="app">
        <record-component />
    </div>
    <div id="bottom">
        <bottom-component />
    </div>
    <!-- Full bundle -->
    <script src="https://unpkg.com/buefy/dist/buefy.min.js"></script>

    <!-- Individual components -->
    <script src="https://unpkg.com/buefy/dist/components/table"></script>
    <script src="https://unpkg.com/buefy/dist/components/input"></script>
    {{-- </div> --}}
</body>
<script src="{{ mix('js/app.js') }}"></script>

</html>