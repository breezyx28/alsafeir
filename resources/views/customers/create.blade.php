<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">إضافة عميل جديد</h2>
    </x-slot>

    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('customers.store') }}" method="POST">
                    @csrf
                    @include('customers.partials.form')
                    <button type="submit" class="btn btn-success">حفظ العميل</button>
                    <a href="{{ route('customers.index') }}" class="btn btn-secondary">إلغاء</a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
