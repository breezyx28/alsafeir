{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">
            سجل الحضور والانصراف
        </h2>
    </x-slot>

    <div class="container py-5">
        @if(session('success'))
            <div class="alert alert-success text-right">
                {{ session('success') }}
            </div>
        @elseif(session('info'))
            <div class="alert alert-info text-right">
                {{ session('info') }}
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger text-right">
                {{ session('error') }}
            </div>
        @endif

        <div class="d-flex justify-content-between mb-3">
            <form action="{{ route('attendance.store') }}" method="POST">
                @csrf
                <button class="btn btn-primary">
                    تسجيل حضور / انصراف
                </button>
            </form>
        </div>

        <div class="card">
            <div class="card-header text-right">
                <strong>سجلات الحضور</strong>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered text-center">
                    <thead class="thead-light">
                        <tr>
                            <th>التاريخ</th>
                            <th>وقت الحضور</th>
                            <th>وقت الانصراف</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attendances as $attendance)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($attendance->date)->format('Y-m-d') }}</td>
                                <td>{{ $attendance->check_in?->format('H:i:s') }}</td>
                                <td>{{ $attendance->check_out?->format('H:i:s') ?? 'لم يسجل بعد' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">لا توجد سجلات حتى الآن.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $attendances->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}
