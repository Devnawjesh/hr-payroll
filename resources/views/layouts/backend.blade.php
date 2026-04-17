<!doctype html>
<html class="no-js" lang="en">
@include('partials.template.head')

<body>
    <div class="wrapper-main">
        @include('partials.template.topbar')
        @include('partials.template.sidebar')
        @yield('content')
        <!-- /.page-content  -->
        @include('partials.template.footer')

    </div>
 
@include('partials.template.footer')	
</body>

</html>