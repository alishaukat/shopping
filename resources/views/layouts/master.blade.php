<!DOCTYPE html>
<html>
    <head>
        <!-- including metas -->
        @section('metas')
            @include('layouts.includes.meta')
        @show
        
        <!-- including title -->
        <title>
        @section('title')
        
        @show
        </title>
        
        <!-- including assets -->
        @section('head_asset')
            @include('layouts.includes.head_assets')
        @show
        
    </head>
    <body>
            <!-- Page Content -->
            <div class="container">
                @include('layouts.includes.nav')
                @include('shared.errors')
                @yield('content')
                @include('layouts.includes.footer')
            </div>
        
        <!-- including assets -->
        @section('foot_asset')
            @include('layouts.includes.foot_assets')
        @show
    </body>
</html>
