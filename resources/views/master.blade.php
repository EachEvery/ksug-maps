<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="csrf-token" value="{{csrf_token()}}" />
<link rel="stylesheet" href="https://use.typekit.net/gow0spk.css" />
<link rel="stylesheet" href="{{mix('css/app.css')}}" type="text/css" />
<link href='https://api.mapbox.com/mapbox-gl-js/v1.8.0/mapbox-gl.css' rel='stylesheet' />


<title>Mapping May 4 | Kent State University</title>
<meta name="title" content="Mapping May 4 | Kent State University">
<meta name="description" content="An interactive map of audio stories surrounding the events of May 4, 1970.">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="https://mappingmay4.kent.edu/">
<meta property="og:title" content="Mapping May 4 | Kent State University">
<meta property="og:description" content="An interactive map of audio stories surrounding the events of May 4, 1970.">
<meta property="og:image" content="{{secure_asset('/images/mm4-share.jpg')}}">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="https://mappingmay4.kent.edu/">
<meta property="twitter:title" content="Mapping May 4 | Kent State University">
<meta property="twitter:description" content="An interactive map of audio stories surrounding the events of May 4, 1970.">
<meta property="twitter:image" content="{{secure_asset('/images/mm4-share.jpg')}}">

<body class="overflow-hidden bg-gray-darker">

    

    <!-- resources/js/KSUGMap.vue gets mounted here -->
    <div id="app" class='relative'></div>

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