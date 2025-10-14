<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __("تعديل تعريف أجر: ") }}{{ $pieceRateDefinition->item_type }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route("piece_rate_definitions.update", $pieceRateDefinition->id) }}" method="POST">
                        @csrf
                        @method("PUT")
                        <div class="form-group mb-3">
                            <label for="item_type">نوع القطعة:</label>
                            <input type="text" class="form-control" id="item_type" name="item_type" value="{{ old("item_type", $pieceRateDefinition->item_type) }}" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="rate">الأجر:</label>
                            <input type="number" step="0.01" class="form-control" id="rate" name="rate" value="{{ old("rate", $pieceRateDefinition->rate) }}" required>
                        </div>
                        <button type="submit" class="btn btn-success">تحديث</button>
                        <a href="{{ route("piece_rate_definitions.index") }}" class="btn btn-secondary">إلغاء</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>