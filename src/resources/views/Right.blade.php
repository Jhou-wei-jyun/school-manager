<!DOCTYPE html>
<html lang="zh-hant-TW">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.0.0/animate.min.css" />
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    <title>i-lolly</title>
</head>

<body id="page-top">
    <div id='wrapper'>
        <div id="nav">
            <nav-component />
        </div>
        <div id="content-wrapper" class="d-flex flex-column  mainbody">
            <div id="content">

                <div id="app">
                    <right-component />
                </div>

            </div>
        </div>

    </div>

    <!-- Full bundle -->
    <script src="https://unpkg.com/buefy/dist/buefy.min.js"></script>

    <!-- Individual components -->
    <script src="https://unpkg.com/buefy/dist/components/table"></script>
    <script src="https://unpkg.com/buefy/dist/components/input"></script>
</body>
{{-- <footer style="height: 10%; background-color:red;">wwww</footer> --}}
<script src="{{ mix('js/app.js') }}"></script>

</html>