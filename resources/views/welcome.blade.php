<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>PhonePe Integration</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
       
    </head>
    <body class="antialiased">
        <div class="col-md-6 mb-4 cursor-pointer">
            <div class="card">
                <div class="card-body h--100px">
                    {{-- <button class="btn btn-block click-if-alone" id="phonepay_button" onclick="BkashPayment()">
                        <img width="100" src="{{asset('img/phonepay.png')}}"/>
                    </button> --}}
                    <a class="btn btn-block click-if-alone" href="{{ route('pay-phonepay', ['customer_id' => 1, 'order_id' => 1]) }}">
                        <img class="initial--40" src="{{asset('img/phonepay1.png')}}"/>
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
