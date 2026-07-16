<div>
    @vite('resources/css/login.css')

    <div class="flex min-h-screen font-sans">

        {{-- KIRI: Foto --}}
        <div class="hidden lg:block relative w-[48%] min-h-screen bg-cover bg-center"
             style="background-image: url('{{ asset('images/unnamed.webp') }}');">

            {{-- Overlay --}}
            <div class="absolute inset-0"
                 style="background: linear-gradient(to bottom, rgba(0,0,0,0.05) 0%, rgba(0,0,0,0) 30%, rgba(0,0,0,0.6) 70%, rgba(0,0,0,0.9) 100%)">
            </div>

            {{-- Teks bawah kiri --}}
            <div class="absolute bottom-0 left-0 right-0 p-10 text-white z-10">

                <div class="flex items-center gap-2 mb-3">
                    <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></div>
                    <span class="text-[0.68rem] font-semibold tracking-widest uppercase opacity-70">
                        SIP Posyandu ·
                    </span>
                </div>

                <h1 class="text-[2rem] font-extrabold leading-tight mb-3 tracking-tight">
                    Layanan Posyandu<br>
                    yang Lebih<br>
                    <span class="text-emerald-300">Terdigitalisasi.</span>
                </h1>

                <p class="text-[0.8rem] opacity-60 leading-relaxed">
                    Platform pencatatan timbang balita, imunisasi, lansia & PMT
                </p>

            </div>
        </div>

        {{-- KANAN: Form --}}
        <div class="flex-1 flex items-center justify-center px-8 py-10 bg-white min-h-screen">
            <div class="w-full max-w-[350px]">

                {{-- Header form --}}
                <div class="mb-8">

                    {{-- Icon --}}
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-5 shadow-lg"
                         style="background: linear-gradient(135deg, #059669, #047857); box-shadow: 0 4px 14px rgba(5,150,105,0.35)">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5 text-white"
                             fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>

                    <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight mb-1">
                        Selamat Datang!
                    </h2>
                    <p class="text-[0.83rem] text-slate-400 leading-relaxed">
                        Masuk ke panel administrasi untuk<br>mengelola data posyandu.
                    </p>

                </div>

                {{-- Form --}}
                <x-filament-panels::form wire:submit="authenticate">
                    {{ $this->form }}
                    <div class="mt-5">
                        <x-filament-panels::form.actions
                            :actions="$this->getCachedFormActions()"
                            :full-width="$this->hasFullWidthFormActions()"
                        />
                    </div>
                </x-filament-panels::form>

                {{-- Divider --}}
                <div class="h-px bg-slate-100 my-7"></div>

                {{-- Footer --}}
                <p class="text-center text-[0.67rem] text-slate-200">
                    &copy; {{ date('Y') }} Sistem Informasi Posyandu
                </p>

            </div>
        </div>

    </div>
</div>
