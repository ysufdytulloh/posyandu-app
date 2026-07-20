<div class="col-span-full space-y-4">

    {{-- HEADER KADER --}}
    <div class="relative bg-white rounded-2xl border border-blue-100 shadow-sm overflow-hidden mb-0">
        {{-- Background gradient dekorasi --}}
        <div class="absolute inset-0 opacity-[0.03]"
            style="background: radial-gradient(ellipse at top right, #2563eb 0%, transparent 70%),
                                radial-gradient(ellipse at bottom left, #1d4ed8 0%, transparent 70%);">
        </div>

        {{-- Garis warna di atas --}}
        <div class="h-1 w-full" style="background: linear-gradient(90deg, #2563eb, #60a5fa, #2563eb);"></div>

        <div class="relative p-5 flex items-center justify-between">
            <div class="flex items-center gap-4">
                {{-- Avatar --}}
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-md"
                    style="background: linear-gradient(135deg, #2563eb, #1d4ed8);">
                    <span class="text-xl font-bold text-white">
                        {{ strtoupper(substr($kader->name, 0, 1)) }}
                    </span>
                </div>

                <div>
                    <p class="text-xs font-semibold text-blue-500 uppercase tracking-wider mb-0.5">Selamat Datang</p>
                    <h2 class="text-lg font-bold text-gray-800 leading-tight">{{ $kader->name }}</h2>
                    <div class="flex items-center gap-1.5 mt-1">
                        <x-filament::icon icon="heroicon-o-building-office" class="w-3.5 h-3.5 text-blue-400"/>
                        <p class="text-xs text-gray-500 font-medium">
                            {{ $posyandu?->nama ?? '-' }}
                            @if($posyandu?->kelurahan)
                                &mdash; Kel. {{ $posyandu->kelurahan }}
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <div class="text-right hidden md:block">
                <p class="text-xs text-gray-400">{{ now()->translatedFormat('l') }}</p>
                <p class="text-sm font-semibold text-gray-700">{{ now()->translatedFormat('d F Y') }}</p>
                <span class="inline-flex items-center gap-1 mt-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-600 border border-blue-100">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                    Kader Aktif
                </span>
            </div>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4">
        @foreach($stats as $stat)
        <div class="group bg-white rounded-2xl border transition-all duration-300 hover:-translate-y-0.5 relative overflow-hidden
            {{ $stat['alert'] ? 'border-red-200 shadow-md shadow-red-50 ring-1 ring-red-200' : 'border-gray-100 shadow-sm hover:shadow-md' }}">

            <div class="h-1 w-full" style="background: {{ $stat['color'] }};"></div>

            <div class="absolute -right-4 -top-4 w-16 h-16 rounded-full opacity-5"
                 style="background: {{ $stat['color'] }}"></div>

            @if($stat['alert'])
            <div class="absolute top-3 right-3">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" style="background: {{ $stat['color'] }}"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5" style="background: {{ $stat['color'] }}"></span>
                </span>
            </div>
            @endif

            <div class="p-4">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                         style="background: {{ $stat['bg'] }}">
                        <x-filament::icon :icon="$stat['icon']" class="w-5 h-5" style="color: {{ $stat['color'] }}"/>
                    </div>
                    <span class="text-2xl font-extrabold text-gray-800">{{ $stat['value'] }}</span>
                </div>
                <p class="text-xs font-bold text-gray-700 leading-tight">{{ $stat['label'] }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $stat['description'] }}</p>

                @if($stat['alert'] && $stat['baru'] > 0)
                <div class="mt-2 pt-2 border-t border-red-100">
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

