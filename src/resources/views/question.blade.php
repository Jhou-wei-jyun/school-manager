<!DOCTYPE html>
<html style="height: 100%;" lang="zh-hant-TW">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css" />
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/2.5.94/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <title>i-lolly</title>
</head>

<body id="page-top">
    <div id='wrapper'>
        <div id="nav">
            <nav-component />
        </div>
        <div id="content-wrapper" class="d-flex flex-column mainbody">
            <div id="content">
                <div id="top">
                    <profile-component />
                </div>
                <!-- <div class="allbody-background"></div> -->
                <div id="app" class='maincontent'>
                    <question-component />
                </div>
                {{-- <div id="bottom">
                        <bottom-component/>
                    </div> --}}
            </div>
        </div>

    </div>
    <script src="{{ mix('js/app.js') }}"></script>
</body>

</html>