@include('backend.layouts.header')
@if(request()->path() !== '/' &&  request()->path() !== '/login')
@if(request()->query('login') === 'true')
@else
@include('backend.layouts.sidebar')
@endif

@endif
@yield('content')
@if(request()->path() !== '/' &&  request()->path() !== '/login')
@include('backend.layouts.footer')
@endif

