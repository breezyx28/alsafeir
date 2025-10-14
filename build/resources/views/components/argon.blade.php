<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'لوحة التحكم' }}</title>
    <link href="{{ asset('build/assets/css/argon-dashboard-tailwind.min.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-100">

    {{-- الشريط الجانبي الثابت (لاحقًا) --}}
    {{-- @include('components.sidebar') --}}

    <main class="p-4">
        {{ $slot }}
    </main>

</body>
</html>
