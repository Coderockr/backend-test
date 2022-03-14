<html>
<head>
    <title>{{ config('app.name') }} | Api</title>
    <link href="{{asset('swagger/style.css')}}" rel="stylesheet">
</head>
<body>
<div id="swagger-ui"></div>
<script src="{{asset('swagger/jquery-2.1.4.min.js')}}"></script>
<script src="{{asset('swagger/swagger-bundle.js')}}"></script>
<script type="application/javascript">
    const ui = SwaggerUIBundle({
        url: "{{ asset('swagger/swagger.yaml') }}",
        dom_id: '#swagger-ui',
    });
</script>
</body>
</html>
