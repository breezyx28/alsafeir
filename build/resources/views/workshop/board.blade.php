<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            لوحة متابعة الإنتاج (المشغل)
        </h2>
    </x-slot>

    <div>
        {{-- استدعاء مكون Livewire هنا --}}
        <livewire:production-board />
    </div>
</x-app-layout>
