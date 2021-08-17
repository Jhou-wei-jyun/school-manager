<!DOCTYPE html>
<html lang="zh-hant-TW">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Material</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.1.45/css/materialdesignicons.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css"/>
        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
        <div id="nav">
            <nav-component/>
        </div>
    </head>
    <body style="background-color:#F0F0F0; height:1000px;">
        <div>
            <div id="app" >
                <material-component/>
            </div>
            <!-- Full bundle -->
            <script src="https://unpkg.com/buefy/dist/buefy.min.js"></script>

            <!-- Individual components -->
            <script src="https://unpkg.com/buefy/dist/components/table"></script>
            <script src="https://unpkg.com/buefy/dist/components/input"></script>
            <script src="https://cdn.jsdelivr.net/npm/vue"></script>
            <script src="//unpkg.com/element-ui@2.3.9/lib/index.js"></script>
        </div>
    </body>
    <script src="{{ mix('js/app.js') }}"></script>
</html>
