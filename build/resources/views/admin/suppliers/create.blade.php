<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">إضافة مورد جديد</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.suppliers.store') }}">
                @csrf
                @include('admin.suppliers.form')
                <div class="mt-4">
                    <button type="submit" class="btn btn-success">حفظ</button>
                    <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary">رجوع</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
