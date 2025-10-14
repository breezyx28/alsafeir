<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'لوحة التحكم' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- ✅ ربط ملف CSS الرئيسي --}}
    <link href="{{ asset('build/assets/css/argon-dashboard-tailwind.min.css') }}" rel="stylesheet">

    {{-- ✅ الخطوط (مثلاً fontawesome أو غيره لو موجود) --}}
    <link href="{{ asset('build/assets/fonts/fontawesome.css') }}" rel="stylesheet">

    {{-- ✅ أيقونات أو تخصيص إضافي --}}
    <link rel="stylesheet" href="{{ asset('build/assets/css/nucleo-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('build/assets/css/nucleo-svg.css') }}">
</head>

<body class="bg-blueGray-50 text-slate-700">
    {{-- ✅ القائمة الجانبية --}}
    @include('layouts.partials.sidebar')

    <main class="relative md:ml-64">
        {{-- ✅ رأس الصفحة --}}
        @include('layouts.partials.navbar')

        {{-- ✅ محتوى الصفحة --}}
        <div class="px-4 md:px-10 mx-auto w-full py-6">
            {{ $slot }}
        </div>
    </main>

    {{-- ✅ JavaScript الخاص بـ Argon --}}
    <script src="{{ asset('build/assets/js/argon-dashboard-tailwind.min.js') }}"></script>
</body>
</html>
