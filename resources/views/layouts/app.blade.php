@include('layouts.partials.header')

<body>
    <div id="app">
        <div id="main" class="layout-horizontal">
            <header class="mb-5">
                @include('layouts.partials.topbar')
                @include('layouts.partials.navbar')
            </header>

            <div class="content-wrapper container">
                @yield('content')
            </div>

            @include('layouts.partials.footer')
        </div>
    </div>
    @include('layouts.partials.script')
    @stack('scriptjs')

</body>

</html>
