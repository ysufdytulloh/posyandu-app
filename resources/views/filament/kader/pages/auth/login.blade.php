<div>
    @vite('resources/css/login.css')

    {{-- <style>
        /* Override hijau → biru untuk panel kader */
        .fi-btn-color-primary.fi-btn {
            background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
            box-shadow: 0 4px 18px rgba(37, 99, 235, 0.35) !important;
        }
        .fi-btn-color-primary.fi-btn:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af) !important;
            box-shadow: 0 6px 26px rgba(37, 99, 235, 0.45) !important;
        }
        .fi-input-wrp:focus-within {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1) !important;
        }
        .fi-checkbox-input:checked {
            background-color: #2563eb !important;
            border-color: #2563eb !important;
        }
    </style> --}}

    <div class="flex min-h-screen font-sans">

        {{-- KIRI: Foto --}}
        <div class="hidden lg:block relative w-[48%] min-h-screen bg-cover bg-center"
             style="background-image: url('{{ asset('images/kepanjangan-posyandu-adalah.jpg.webp') }}');">

            {{-- Overlay --}}
            <div class="absolute inset-0"
                 style="background: linear-gradient(to bottom, rgba(0,0,0,0.05) 0%, rgba(0,0,0,0) 30%, rgba(0,0,0,0.6) 70%, rgba(0,0,0,0.9) 100%)">
            </div>

            {{-- Teks bawah kiri --}}
            <div class="absolute bottom-0 left-0 right-0 p-10 text-white z-10">

                <div class="flex items-center gap-2 mb-3">
                    <div class="w-1.5 h-1.5 bg-[#1d4ed8] rounded-full"></div>
                    <span class="text-[0.68rem] font-semibold tracking-widest uppercase opacity-70">
                        SIP Posyandu · Panel Kader · 2026
                    </span>
                </div>

                <h1 class="text-[2rem] font-extrabold leading-tight mb-3 tracking-tight">
                    Layanan Posyandu<br>
                    yang Lebih<br>
                    <span class="text-[#2563eb]">Terdigitalisasi.</span>
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

                    {{-- Icon biru kader --}}
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center mb-5 shadow-lg"
                         style="background: linear-gradient(135deg, #2563eb, #1d4ed8); box-shadow: 0 4px 14px rgba(37,99,235,0.35)">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-5 h-5 text-white"
                             fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>

                    <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight mb-1">
                        Selamat Datang, Kader!
                    </h2>
                    <p class="text-[0.83rem] text-slate-400 leading-relaxed">
                        Masuk ke panel kader untuk mengelola data posyandu.
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
                <div class="h-px bg-slate-100 my-5"></div>

                {{-- Footer --}}
                <p class="text-center text-[0.67rem] text-slate-200">
                    &copy; {{ date('Y') }} Sistem Informasi Posyandu
                </p>

                {{-- Link ke Admin --}}
                <p class="text-center text-[0.75rem] text-slate-400 mt-2">
                    Login sebagai Admin?
                    <a href="/admin" class="text-blue-600 hover:text-blue-700 font-semibold hover:underline ml-1">
                        Masuk sebagai Admin
                    </a>
                </p>

            </div>
        </div>

    </div>
</div>
