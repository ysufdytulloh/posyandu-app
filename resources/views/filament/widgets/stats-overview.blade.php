<div class="col-span-full">
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4">
        @foreach($stats as $stat)
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-200">
            <div class="h-1" style="background: {{ $stat['color'] }}"></div>
            <div class="p-4">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0"
                         style="background: {{ $stat['bg'] }}">
                        <x-filament::icon
                            :icon="$stat['icon']"
                            class="w-4 h-4"
                            style="color: {{ $stat['color'] }}"
                        />
                    </div>
                    <span class="text-2xl font-bold text-gray-700">{{ $stat['value'] }}</span>
                </div>
                <p class="text-xs font-semibold text-gray-600 leading-tight">{{ $stat['label'] }}</p>
                <p class="text-xs text-gray-400 mt-0.5 leading-tight">{{ $stat['description'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>
