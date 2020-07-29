<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>QA Checker</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    {{-- Compiled version of Stylesheets --}}
    {{-- <link rel="stylesheet" href="{{ asset('css/_libraries.css') }}">
    --}}
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
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


    {{-- JQuery CDN --}}
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>

    {{-- Popper JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>

    {{-- Compiled version of the Javascript --}}
    {{-- <script src="{{ asset('js/test.js') }}"></script> --}}
    {{-- <script src="{{ asset('js/app.js') }}"></script> --}}
</body>

</html>
