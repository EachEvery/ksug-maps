<title>Mapping May 4 | Kent State University Department of Geography</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<meta name="csrf-token" value="{{csrf_token()}}" />
<link rel="stylesheet" href="https://use.typekit.net/gow0spk.css">


<link rel="stylesheet" href="{{mix('css/app.css')}}" />

<body class="overflow-hidden bg-gray-darker">

    <!-- resources/js/KSUGMap.vue gets mounted here, then it's Vue on out 💪 -->
    <div id="app"></div>

    <script>
        window.isAdmin = {{Auth::check() ? 'true' : 'false'}}
    </script>
    <script type="text/javascript" src="{{mix('js/app.js')}}"></script>
</body>