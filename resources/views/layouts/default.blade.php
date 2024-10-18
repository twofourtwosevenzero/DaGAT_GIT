<head>
    
@include('includes.head')

</head>


<body>
    @include('includes.sidebar')


    {{-- Codes here for main contents for different pages --}}
<div class="main-content">
  @yield('content')
</div>


</body>
</html>