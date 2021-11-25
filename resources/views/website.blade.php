<!DOCTYPE html>
<html lang="en">
    <head>
        <title>@yield('title')</title>
        @stack('meta')
        @include('website.templates.headers')
        @stack('styles')
    </head>
    <body id="body">
        @include('website.templates.navbar')
        @yield('body')
        @include('website.templates.footer')
        <script type="text/javascript" src="{{ asset(mix('js/website/all.js')) }}"></script>
        @stack('scripts')
        <script type="text/javascript">
            var font_conf = {
                google: { families: ['Poppins:wght@300;400;500&display=swap'] },
                timeout: 4000
            };

            WebFont.load(font_conf);
        </script>
    </body>
</html>
