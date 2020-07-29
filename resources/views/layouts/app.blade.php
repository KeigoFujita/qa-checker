<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>QA Checker</title>

    <!-- Fonts -->
    <link href="" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        crossorigin="anonymous">
    {{-- <link rel="stylesheet" href="{{ asset('css/_libraries.css') }}">
    --}}
    {{-- <link rel="stylesheet" href="{{ asset('css/styles.css') }}"> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/KeigoFujita/qa-checker/public/css/styles.css"
        crossorigin="anonymous">
</head>

<body>
    @include('partials.navbar')
    <main>
        <div class="container mt-5">
            <div class="row">
                <div class="col-md-4 mb-3 md-mb-4">
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

    {{-- Bootstrap JS Bundles --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" crossorigin="anonymous">
    </script>

    {{-- Custom Script --}}
    <script src="https://cdn.jsdelivr.net/gh/KeigoFujita/qa-checker/public/js/test.js" crossorigin="anonymous"></script>
</body>

</html>