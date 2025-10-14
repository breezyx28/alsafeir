<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">ربط المستخدمين بالموظفين</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('users.assign') }}" class="mb-4">
                @csrf

                <div class="mb-3">
                    <label>اختر المستخدم</label>
                    <select name="user_id" class="form-control">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->email }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label>اختر الموظف</label>
                    <select name="employee_id" class="form-control">
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->name }} - {{ $employee->position }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">ربط</button>
            </form>

            <h3 class="mt-6">قائمة المستخدمين المرتبطين</h3>
            <ul class="list-group mt-2">
                @foreach($users as $user)
                    <li class="list-group-item">
                        {{ $user->name }} - 
                        {{ $user->employee ? $user->employee->name : 'لا يوجد موظف مرتبط' }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</x-app-layout>
