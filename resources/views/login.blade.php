<!DOCTYPE html>
<html>

<head>

    <title>Laravel 8 Livewire Select2 Dropdown Autocomplete Search Example</title>

    @livewireStyles
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body>

    <div class="container">
    @livewire("panel")
    </div>

</body>

@livewireScripts
@stack('scripts')

</html>