<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/jquery.multiselect.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>


<body>

                        {{-- <livewire:adjust-invoice :customers="$customers"> --}}
                        @livewire('adjust-invoice', ['customers' => $customers])


    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>

    <!-- JS & CSS library of MultiSelect plugin -->
    <script src="{{ asset('js/jquery.multiselect.js') }}"></script>


    <script>
        $('#langOpt').multiselect({
            columns: 1,
            texts: {
                placeholder: 'Select Languages',
                search: 'Search Languages'
            },
            search: true
        })
    </script>
</body>

</html>
