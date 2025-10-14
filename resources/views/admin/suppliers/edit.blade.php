<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">تعديل مورد</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.suppliers.update', $supplier) }}">
                @csrf
                @method('PUT')
                @include('admin.suppliers.form', ['supplier' => $supplier])
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">تحديث</button>
                    <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary">رجوع</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
