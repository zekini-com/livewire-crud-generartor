<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        [x-cloak] {
    display: none;
}
    </style>

	{{-- TODO translatable suffix --}}
    <title>@yield('title', 'Zekini Admin')</title>

    @livewireStyles

    <!-- Scripts -->
    <script src="{{ mix('js/app.js') }}" defer></script>

	@include('zekini/livewire-crud-generator::admin.partials.main-styles')

    @yield('styles')

</head>

<body>
  
    @yield('content')

    @livewireScripts

    @include('zekini/livewire-crud-generator::admin.partials.main-bottom-scripts')
</body>

</html>