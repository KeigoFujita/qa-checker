<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>QA Checker</title>

    <!-- Fonts -->
    <link href="" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/_libraries.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body>
    @include('partials.navbar')
    <main>
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <p class="display-5">Dashboard </p>
                    <div class="card shadow-sm">
                        <div class="card-body d-flex align-items-center">
                            <div>
                                <p class="card-title mb-0">

                                    @isset($sum)
                                        Php. {{ convertToPeso($sum) }}
                                    @else
                                        Php. {{ convertToPeso($total_earnings) }}
                                    @endisset

                                </p>
                                <p class="card-description mb-0">*approximation only</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    @yield('content')
                </div>
            </div>
        </div>
    </main>
    @yield('modal')
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/test.js') }}"></script>
</body>

</html>
