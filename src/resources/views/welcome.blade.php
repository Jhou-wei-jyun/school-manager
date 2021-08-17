<!DOCTYPE html>
<html lang="zh-hant-TW">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- <link rel="stylesheet" href="https://unpkg.com/buefy/dist/buefy.min.css"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css" />
    <title>i-lolly</title>
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/2.5.94/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
</head>

<body>
    <div class="welcome-background"></div>
    <div id="nav">

    </div>

    <div id="app">
        <welcome-component />
    </div>

    <nav class="navbar is-fixed-bottom">
        <div class="contact has-text-centered">
            協雲科技股份有限公司 © 2020 Shoesconn Incorporated. ver 1.7 新聯絡簿後台
        </div>
    </nav>

    <script src="{{ mix('js/app.js') }}"></script>
</body>

</html>