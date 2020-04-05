<title>Mapping May 4 | Kent State University Department of Geography</title>

<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="csrf-token" value="{{csrf_token()}}" />
<link rel="stylesheet" href="https://use.typekit.net/gow0spk.css" />
<link rel="stylesheet" href="{{mix('css/app.css')}}" type="text/css" />
<link href='https://api.mapbox.com/mapbox-gl-js/v1.8.0/mapbox-gl.css' rel='stylesheet' />



<body class="overflow-hidden bg-gray-darker">

    <!-- resources/js/KSUGMap.vue gets mounted here -->
    <div id="app"></div>

    <script>
        window.isAdmin = {{Auth::check() ? 'true' : 'false'}}
        window.phoneNumber = "{{env('PHONE_NUMBER', '(555) 555-5555')}}"
    </script>


    <script src='https://api.mapbox.com/mapbox-gl-js/v1.8.0/mapbox-gl.js'></script>
    <script type="text/javascript" src="{{mix('js/app.js')}}"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery-throttle-debounce/1.1/jquery.ba-throttle-debounce.min.js"></script>    
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/fluidbox/2.0.5/js/jquery.fluidbox.min.js" integrity="sha256-AVBLVWwvNE9uzNP36wynCSm3JIIkjvjXsj/Ol30JzGM=" crossorigin="anonymous"></script>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-151341591-1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'UA-151341591-1');
    </script>
</body>