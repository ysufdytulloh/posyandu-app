<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="theme-color" content="#7c3aed">
    <title>Portal Orang Tua — SIP Posyandu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        * { font-family: 'Inter', sans-serif; box-sizing: border-box; }
        body { background: #f5f3ff; margin: 0; padding: 0; }
    </style>
</head>
<body>
    {{ $slot }}
    @livewireScripts
</body>
</html>
