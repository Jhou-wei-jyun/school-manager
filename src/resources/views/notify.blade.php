<!DOCTYPE html>
<html style="background-color:#eef1f5; height:100%;" lang="zh-hant-TW">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>notify</title>
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/2.5.94/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
</head>

<body id="page-top">
    <div id='wrapper'>
        <div id="nav">
            <nav-component />
        </div>
        <div id="content-wrapper" class="d-flex flex-column mainbody">
            <div id="content">
                <div id="app" class='maincontent'>
                    <notify-component />
                </div>
            </div>
        </div>

    </div>
    <script src="{{ mix('js/app.js') }}"></script>
</body>

</html>