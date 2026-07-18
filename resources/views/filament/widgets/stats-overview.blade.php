<div class="col-span-full space-y-4">

    {{-- HEADER + FILTER --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-base font-bold text-gray-800">Statistik Posyandu</h2>
            <p class="text-xs text-gray-400 mt-0.5">
                {{ now()->translatedFormat('l, d F Y') }}
                @if($posyandu_id)
                    &mdash; <span class="text-primary-600 font-medium">{{ $posyandus[$posyandu_id] ?? '' }}</span>
                @else
                    &mdash; <span class="text-gray-500">Semua Posyandu</span>
                @endif
            </p>
        </div>
        <div class="flex items-center gap-2">
            <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-xl px-3 py-2 shadow-sm">
                <x-filament::icon icon="heroicon-o-building-office" class="w-4 h-4 text-gray-400"/>
                <select
                    wire:model.live="posyandu_id"
                    class="text-sm text-gray-600 bg-transparent border-none outline-none focus:ring-0 cursor-pointer pr-2">
                    <option value="">Semua Posyandu</option>
                    @foreach($posyandus as $id => $nama)
                        <option value="{{ $id }}">{{ $nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4">
        @foreach($stats as $stat)
        <div class="group bg-white rounded-2xl border transition-all duration-300 hover:-translate-y-0.5 relative overflow-hidden
            {{ $stat['alert'] ? 'border-red-200 shadow-md shadow-red-50 ring-1 ring-red-200' : 'border-gray-100 shadow-sm hover:shadow-md hover:border-gray-200' }}">

            {{-- Top color bar --}}
            <div class="h-1 w-full" style="background: linear-gradient(90deg, {{ $stat['color'] }}, {{ $stat['color'] }}99);"></div>

            {{-- Background decoration --}}
            <div class="absolute -right-4 -top-4 w-16 h-16 rounded-full opacity-5"
                 style="background: {{ $stat['color'] }}"></div>

            {{-- Pulse alert --}}
            @if($stat['alert'])
            <div class="absolute top-3 right-3">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" style="background: {{ $stat['color'] }}"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5" style="background: {{ $stat['color'] }}"></span>
                </span>
            </div>
            @endif

            <div class="p-4">
                {{-- Icon + Value --}}
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 transition-transform duration-200 group-hover:scale-110"
                         style="background: {{ $stat['bg'] }}">
                        <x-filament::icon
                            :icon="$stat['icon']"
                            class="w-5 h-5"
                            style="color: {{ $stat['color'] }}"
                        />
                    </div>
                    <div class="text-right">
                        <span class="text-2xl font-extrabold text-gray-800 leading-none">{{ $stat['value'] }}</span>
                    </div>
                </div>

                {{-- Label & Description --}}
                <p class="text-xs font-bold text-gray-700 leading-tight">{{ $stat['label'] }}</p>
                <p class="text-xs text-gray-400 mt-0.5 leading-tight">{{ $stat['description'] }}</p>

                {{-- Alert badge --}}
                @if($stat['alert'] && $stat['baru'] > 0)
                <div class="mt-2.5 pt-2.5 border-t border-red-100">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-lg text-xs font-semibold bg-red-50 text-red-600">
                        <x-filament::icon icon="heroicon-o-bell-alert" class="w-3 h-3"/>
                        {{ $stat['baru'] }} baru bulan ini
                    </span>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
