<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    @viteReactRefresh
    @vite('resources/js/app.tsx')
    @inertiaHead
    @routes
      <link href="{{asset('img/favicon.ico')}}" rel="icon" /> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" />

  </head>
  <body>
    @inertia
    <script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>

  </body>
</html>