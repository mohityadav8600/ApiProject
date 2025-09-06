<!DOCTYPE html>
<html lang="en">
<head>
   

    {{-- Laravel CSRF Token --}}


    {{-- Styles (using Vite or Mix) --}}
   
<body>
    @include('layouts.header')
    @include('layouts.sidebar')

    <main class="main-content">
        @yield('content')
    </main>

    @include('layouts.footer')

    {{-- Extra scripts if needed --}}
  
</body>
</html>