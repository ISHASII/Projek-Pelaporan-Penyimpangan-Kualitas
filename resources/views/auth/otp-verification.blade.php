<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Verifikasi OTP</title>

    {{-- Offline Assets --}}
    <script src="{{ asset('assets/js/tailwind.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <script src="{{ asset('assets/js/chart.min.js') }}"></script>
    <script src="{{ asset('assets/js/flatpickr.min.js') }}"></script>

    <style>
        body {
            background:
                linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(147, 51, 234, 0.1) 50%, rgba(236, 72, 153, 0.1) 100%),
                url('{{ asset("image/PT.webp") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
        }

        .glass-card {
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow:
                0 25px 45px rgba(0, 0, 0, 0.1),
                0 0 0 1px rgba(255, 255, 255, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .glass-input {
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            background: rgba(255, 255, 255, 0.25);
            border: 1px solid rgba(255, 255, 255, 0.4);
            color: #1f2937;
            box-shadow:
                0 8px 32px rgba(31, 38, 135, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }

        .glass-input:focus {
            background: rgba(255, 255, 255, 0.4);
            border-color: rgba(239, 68, 68, 0.6);
            box-shadow:
                0 0 0 2px rgba(239, 68, 68, 0.15),
                0 8px 32px rgba(31, 38, 135, 0.15),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
        }

        .otp-input {
            width: 3rem;
            height: 3rem;
            font-size: 1.2rem;
            text-align: center;
            border-radius: 0.75rem;
        }

        @media (max-width: 640px) {
            .otp-input {
                width: 2.5rem;
                height: 2.5rem;
                font-size: 1rem;
            }
        }

        @media (max-width: 420px) {
            .glass-card {
                padding: 1.25rem;
                border-radius: 1rem;
                margin: 0 12px;
            }

            .glass-logo img {
                width: 56px;
            }

            h1 {
                font-size: 1.05rem;
            }

            .otp-input {
                width: 2rem;
                height: 2rem;
                font-size: 0.95rem;
                border-radius: 0.5rem;
            }

            #otp-input-container {
                gap: 0.5rem !important;
                padding: 0 6px;
            }



            .glass-button {
                padding-top: 0.6rem;
                padding-bottom: 0.6rem;
                font-size: 0.95rem;
            }

            #resend-btn {
                padding: 0.45rem 0.9rem;
                font-size: 0.9rem;
            }

            body {
                padding-top: 6px;
                padding-bottom: 24px;
            }
        }

        .glass-button {
            backdrop-filter: blur(13px);
            -webkit-backdrop-filter: blur(10px);
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.85), rgba(220, 38, 38, 0.95));
            border: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow:
                0 15px 35px rgba(239, 68, 68, 0.25),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }

        .glass-button:hover {
            background: linear-gradient(135deg, rgba(220, 38, 38, 1), rgba(185, 28, 28, 1));
            transform: translateY(-2px);
        }

        .glass-logo {
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            padding: 0.75rem;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center px-4 py-10">

    <div class="w-full max-w-md">
        <div class="glass-card rounded-3xl p-6">

            <div class="flex justify-center mb-4">
                <div class="glass-logo">
                    <img src="{{ asset('image/kayaba.webp') }}" class="w-24 object-contain">
                </div>
            </div>

            <h1 class="text-xl font-semibold text-center text-gray-800">Verifikasi Kode OTP</h1>
            <p class="text-sm text-center text-gray-600 mt-1 mb-4">Masukkan 6 digit kode yang dikirim ke nomor Anda.</p>



            @if($errors->has('otp_code'))
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 rounded">{{ $errors->first('otp_code') }}
                </div>
            @endif

            <form method="POST" action="{{ route('otp.verify') }}">
                @csrf
                <div id="otp-input-container" class="flex justify-center gap-3 mb-5"></div>
                <input type="hidden" name="otp_code" id="hidden-otp-code">

                <button type="submit"
                    class="glass-button w-full text-white py-3 rounded-xl font-semibold text-base mt-2 transition-all">
                    Verifikasi
                </button>

                <a href="{{ route('login') }}"
                    class="mt-3 block w-full text-center py-2.5 border border-gray-300 rounded-xl text-white hover:bg-white/30 transition">
                    Kembali ke Login
                </a>
            </form>

            <form method="POST" action="{{ route('otp.resend') }}" id="resend-form" class="mt-5 text-center">
                @csrf
                <button type="submit" id="resend-btn"
                    class="px-4 py-2 border border-gray-300 rounded-xl text-white text-sm hover:bg-white/30 transition disabled:opacity-50">
                    Kirim ulang kode
                </button>
                <span id="resend-timer" class="ml-2 text-sm text-gray-500"></span>
            </form>

            <div id="resend-response" class="mt-2 text-sm text-white"></div>

            <p class="text-center text-white text-xs mt-3">
                © {{ date('Y') }} PT Kayaba Indonesia
            </p>
        </div>

    </div>
    <script>
        (function () {
            const container = document.getElementById('otp-input-container');
            const hidden = document.getElementById('hidden-otp-code');
            const digits = 6;

            function createInput(i) {
                const input = document.createElement('input');
                input.type = 'text';
                input.maxLength = 1;
                input.className = 'otp-input glass-input';

                input.addEventListener('input', (e) => {
                    e.target.value = e.target.value.replace(/[^0-9]/g, '');
                    if (e.target.value && i < digits - 1) container.children[i + 1].focus();
                    updateHidden();
                });

                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && e.target.value === '' && i > 0)
                        container.children[i - 1].focus();
                });

                return input;
            }

            for (let i = 0; i < digits; i++) container.appendChild(createInput(i));

            function updateHidden() {
                let val = '';
                for (let i = 0; i < digits; i++) val += (container.children[i].value || '');
                hidden.value = val;
            }

            if (container.children.length) container.children[0].focus();
        })();

        (function () {
            const form = document.getElementById('resend-form');
            if (!form) return;
            const btn = document.getElementById('resend-btn');
            const timer = document.getElementById('resend-timer');
            const resp = document.getElementById('resend-response');

            form.addEventListener('submit', function (e) {
                e.preventDefault();
                if (!btn) return;
                btn.disabled = true;
                const originalText = btn.textContent;
                btn.textContent = 'Mengirim...';
                if (resp) { resp.textContent = ''; }

                const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                const token = tokenMeta ? tokenMeta.getAttribute('content') : '';

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({})
                }).then(async (r) => {
                    btn.disabled = false;
                    btn.textContent = originalText;
                    if (r.ok) {
                        const data = await r.json();
                        if (resp) resp.textContent = data.message || 'Berhasil dikirim.';


                    } else {
                        let txt = await r.text();
                        try { const j = JSON.parse(txt); txt = j.message || (j.error || JSON.stringify(j)); } catch (_) { }
                        if (resp) resp.textContent = txt || 'Gagal mengirim.';
                    }
                }).catch((err) => {
                    btn.disabled = false;
                    btn.textContent = originalText;
                    if (resp) resp.textContent = 'Terjadi kesalahan jaringan.';
                });
            });
        })();
    </script>
</body>

</html>