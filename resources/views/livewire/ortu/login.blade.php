<div>
    <div class="min-h-screen flex items-center justify-center p-4" style="background: #f5f3ff;">
        <div class="w-full max-w-sm">

            {{-- Ilustrasi / Header --}}
            <div class="text-center mb-6">
                <div style="position:relative; display:inline-block; margin-bottom:16px;">
                    {{-- Background circle dekorasi --}}
                    <div style="width:120px; height:120px; border-radius:50%; background:linear-gradient(135deg,#7c3aed,#5b21b6); margin:0 auto; display:flex; align-items:center; justify-content:center; position:relative;">
                        {{-- Icon keluarga --}}
                        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                        {{-- Badge ungu kecil --}}
                        <div style="position:absolute; bottom:-4px; right:-4px; width:32px; height:32px; border-radius:50%; background:#fff; display:flex; align-items:center; justify-content:center; box-shadow:0 2px 8px rgba(124,58,237,0.3);">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Floating card kecil dekorasi --}}
                    <div style="position:absolute; top:0; left:-20px; background:#fff; border-radius:12px; padding:8px 10px; box-shadow:0 4px 12px rgba(124,58,237,0.15); border:1px solid #ede9fe;">
                        <p style="font-size:10px; color:#7c3aed; margin:0; font-weight:600;">BB Normal</p>
                        <p style="font-size:16px; color:#1e1b4b; margin:0; font-weight:700;">9.5 kg</p>
                    </div>

                    <div style="position:absolute; top:10px; right:-24px; background:#fff; border-radius:12px; padding:8px 10px; box-shadow:0 4px 12px rgba(124,58,237,0.15); border:1px solid #ede9fe;">
                        <p style="font-size:10px; color:#7c3aed; margin:0; font-weight:600;">Imunisasi</p>
                        <p style="font-size:16px; color:#1e1b4b; margin:0; font-weight:700;">✓ BCG</p>
                    </div>
                </div>

                <h1 class="text-2xl font-bold" style="color:#1e1b4b; margin-top:8px;">Portal Orang Tua</h1>
                <p class="text-sm mt-1" style="color:#7c3aed;">Pantau kesehatan anak Anda</p>
            </div>

            {{-- Card Login --}}
            <div class="bg-white rounded-2xl p-6" style="border:1px solid #ede9fe; box-shadow:0 4px 24px rgba(124,58,237,0.1);">
                <h2 class="text-lg font-bold mb-1" style="color:#1e1b4b;">Masuk</h2>
                <p class="text-sm mb-6" style="color:#6b7280;">Lihat data kesehatan anak Anda</p>

                @if($error)
                <div style="background:#fee2e2; border:1px solid #fecaca; color:#991b1b; font-size:14px; border-radius:12px; padding:12px; margin-bottom:16px;">
                    {{ $error }}
                </div>
                @endif

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#4b5563;">Email</label>
                        <input wire:model="email" type="email" placeholder="email@contoh.com"
                               style="width:100%; padding:12px 16px; border-radius:12px; border:1px solid #e5e7eb; font-size:14px; outline:none; box-sizing:border-box;"
                               onfocus="this.style.borderColor='#7c3aed'; this.style.boxShadow='0 0 0 3px rgba(124,58,237,0.1)'"
                               onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                        @error('email') <p style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1.5" style="color:#4b5563;">Password</label>
                        <div style="position:relative;">
                            <input wire:model="password" id="password-input" type="password" placeholder="••••••••"
                                style="width:100%; padding:12px 48px 12px 16px; border-radius:12px; border:1px solid #e5e7eb; font-size:14px; outline:none; box-sizing:border-box;"
                                onfocus="this.style.borderColor='#7c3aed'; this.style.boxShadow='0 0 0 3px rgba(124,58,237,0.1)'"
                                onblur="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'">
                            <button type="button" onclick="
                                var input = document.getElementById('password-input');
                                var eyeOn = document.getElementById('eye-on');
                                var eyeOff = document.getElementById('eye-off');
                                if (input.type === 'password') {
                                    input.type = 'text';
                                    eyeOn.style.display = 'none';
                                    eyeOff.style.display = 'block';
                                } else {
                                    input.type = 'password';
                                    eyeOn.style.display = 'block';
                                    eyeOff.style.display = 'none';
                                }
                            " style="position:absolute; right:14px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; padding:0; color:#9ca3af;">
                                <svg id="eye-on" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                                </svg>
                                <svg id="eye-off" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                    <line x1="1" y1="1" x2="23" y2="23"/>
                                </svg>
                            </button>
                        </div>
                        @error('password') <p style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</p> @enderror
                    </div>

                    <button wire:click="login"
                            style="width:100%; padding:14px; border-radius:12px; border:none; color:#fff; font-weight:600; font-size:14px; cursor:pointer; background:linear-gradient(135deg,#7c3aed,#6d28d9);">
                        Masuk
                    </button>
                </div>

                <p style="text-align:center; font-size:12px; color:#9ca3af; margin-top:20px;">
                    Hubungi admin posyandu jika belum punya akun
                </p>
            </div>

            <p style="text-align:center; font-size:11px; color:#a78bfa; margin-top:12px;">
                &copy; {{ date('Y') }} Sistem Informasi Posyandu
            </p>
        </div>
    </div>
</div>
