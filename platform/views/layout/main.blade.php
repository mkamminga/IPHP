<!DOCTYPE html>
<html>
    <head>

        <title>Goldenfingers - @yield("title", "Default")</title>
        @section("styles")
            <link rel="stylesheet" href="/css/foundation.min.css" />
            <link rel="stylesheet" href="/css/main.css" />
            <link rel="stylesheet" href="/css/foundation-icons.css" />
        @show
        <script src="/js/vendor/modernizr.js"></script>
    </head>

    <body>
        @yield("content")
    </body>
</html>
