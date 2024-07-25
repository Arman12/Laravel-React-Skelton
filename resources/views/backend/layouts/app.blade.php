<!DOCTYPE html>
<html lang="en">
    @include('backend.includes.head')
  
  <body>
    <div class="container-scroller">
        @include('backend.includes.header')
        @stack('styles')
        <div class="container-fluid page-body-wrapper">
            @include('backend.includes.sidebar')
            <div class="main-panel">
                @yield('content')
                @include('backend.includes.footer')

            </div>
        </div>
    </div>
    @include('backend.includes.scripts')
    @stack('scripts')
  </body>
</html>