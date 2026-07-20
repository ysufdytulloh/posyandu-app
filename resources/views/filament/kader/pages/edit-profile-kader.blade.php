<x-filament-panels::page>

<x-filament::section>
    <x-slot name="heading">Informasi Akun</x-slot>
    <x-slot name="description">Update nama, email, dan password akun kamu</x-slot>

    <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-100">
        <div class="w-16 h-16 rounded-2xl bg-blue-100 flex items-center justify-center">
            <span class="text-2xl font-bold text-blue-600">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </span>
        </div>
        <div>
            <p class="text-lg font-bold text-gray-800">{{ auth()->user()->name }}</p>
            <p class="text-sm text-gray-500">{{ auth()->user()->email }}</p>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium mt-1 bg-blue-100 text-blue-700">
                Kader
            </span>
        </div>
    </div>

    {{ $this->form }}

    <div class="flex justify-end mt-6 pt-4 border-t border-gray-100">
        <x-filament::button
            wire:click="save"
            icon="heroicon-o-check"
            size="md">
            Simpan Perubahan
        </x-filament::button>
    </div>
</x-filament::section>

</x-filament-panels::page>
