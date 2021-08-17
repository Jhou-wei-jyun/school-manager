<!DOCTYPE html>
<html lang="zh-hant-TW">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css"/>
        <link rel="stylesheet" href="https://cdn.materialdesignicons.com/2.5.94/css/materialdesignicons.min.css">
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
        <title>newEmployee</title>
       <style>
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
        </style>
    </head>
    <body style="background-color:#E0E0E0; height:100%;">
        {{-- <div class="flex-center position-ref">
            <div class="content"> --}}
                {{-- <div class="title m-b-md">
                    <h1><span style="color:#003060; font-size:70px;">新增員工</span></h1>
                </div> --}}
                <div id="nav">
                    <nav-component/>
                </div>
                <div id="app" style="padding-top: 110px;">
                    <new-employee-component/>
                </div>
                {{-- <!-- Full bundle -->
                <script src="https://unpkg.com/buefy/dist/buefy.min.js"></script>

                <!-- Individual components -->
                <script src="https://unpkg.com/buefy/dist/components/table"></script>
                <script src="https://unpkg.com/buefy/dist/components/input"></script> --}}
            {{-- </div>
        </div> --}}
        <script src="{{ mix('js/app.js') }}"></script>
    </body>

</html>
