<!DOCTYPE html>
<html lang="zh-hant-TW">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        {{-- <link rel="stylesheet" href="https://unpkg.com/buefy/dist/buefy.min.css"> --}}
        {{-- <script src="https://unpkg.com/vue"></script> --}}

        <title>Redis</title>
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
    <body style="background-color:#E0E0E0; height:1500px;">
        <div class="flex-center position-ref">

            {{--  --}}
            <div class="content">
                <div class="title m-b-md">
                    <h1><span style="color:#003060; font-size:70px;">Redis</span></h1>
                </div>
                <div id="app">
                    <redis-component/>
                </div>
                <!-- Full bundle -->
                <script src="https://unpkg.com/buefy/dist/buefy.min.js"></script>

                <!-- Individual components -->
                <script src="https://unpkg.com/buefy/dist/components/table"></script>
                <script src="https://unpkg.com/buefy/dist/components/input"></script>
            </div>
        </div>
    </body>
</html>
<script src="{{ mix('js/app.js') }}"></script>
