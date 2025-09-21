<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <meta name="author" content="Web Master César Cancino Zapata | yo@cesarcancino.com" />
    <meta name="keywords" content="php, java, javascript, typescript, ecmascript, js, html, html 5, php 7, cesar cancino, César Cancino, videotutoriales, spring boot, spring web mvc, struts, apache, mysql, web, programación, desarrollo web, desarrollo, css, laravel, codeigniter, synfony, Zend Framework 2, jsp, jstl, hibernate, jpa, curl, soap, ldap, cups, bootstrap, python, dhango, django 2, django 3, python2, python3, angular"
    />
    <meta name="description" content="Blog personal de César Cancino Zapata" />
    @viteReactRefresh
    @vite('resources/js/app.tsx')
    @inertiaHead
    @routes
    <link rel="icon" href="{{asset('img/favicon.ico')}}" />
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}" />
  </head>
  <body>
    @inertia
    <script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
  </body>
</html>