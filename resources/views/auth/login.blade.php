<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="{{ asset('assets/js/tailwind.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
</head>

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
        backdrop-filter: blur(13px);
        -webkit-backdrop-filter: blur(20px);
        background: rgba(255, 255, 255, 0.25);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow:
            0 25px 45px rgba(0, 0, 0, 0.1),
            0 0 0 1px rgba(255, 255, 255, 0.05),
            inset 0 1px 0 rgba(255, 255, 255, 0.1);
    }

    .glass-input {
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow:
            0 8px 32px rgba(31, 38, 135, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 0.2);
    }

    .glass-input:focus {
        background: rgba(255, 255, 255, 0.3);
        border-color: rgba(239, 68, 68, 0.5);
        box-shadow:
            0 0 0 2px rgba(239, 68, 68, 0.1),
            0 8px 32px rgba(31, 38, 135, 0.15),
            inset 0 1px 0 rgba(255, 255, 255, 0.3);
    }

    .glass-pw-wrapper {
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow:
            0 8px 32px rgba(31, 38, 135, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 0.2);
    }

    .glass-pw-wrapper:focus-within {
        background: rgba(255, 255, 255, 0.3);
        border-color: rgba(239, 68, 68, 0.5);
        box-shadow:
            0 0 0 2px rgba(239, 68, 68, 0.1),
            0 8px 32px rgba(31, 38, 135, 0.15),
            inset 0 1px 0 rgba(255, 255, 255, 0.3);
    }

    .glass-button {
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.8), rgba(220, 38, 38, 0.9));
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow:
            0 15px 35px rgba(239, 68, 68, 0.2),
            0 5px 15px rgba(0, 0, 0, 0.1),
            inset 0 1px 0 rgba(255, 255, 255, 0.2);
    }

    .glass-button:hover {
        background: linear-gradient(135deg, rgba(220, 38, 38, 0.9), rgba(185, 28, 28, 1));
        transform: translateY(-2px);
        box-shadow:
            0 20px 40px rgba(239, 68, 68, 0.3),
            0 8px 20px rgba(0, 0, 0, 0.15),
            inset 0 1px 0 rgba(255, 255, 255, 0.3);
    }

    .glass-captcha {
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.25);
    }

    .glass-refresh {
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .glass-refresh:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-1px);
    }

    input:-webkit-autofill {
        box-shadow: 0 0 0px 1000px rgba(254, 226, 226, 0.3) inset !important;
        -webkit-text-fill-color: #1f2937 !important;
        backdrop-filter: blur(10px);
    }

    input[type="password"]::-ms-clear,
    input[type="password"]::-ms-reveal {
        display: none;
        width: 0;
        height: 0;
    }

    input[type="password"]::-webkit-textfield-decoration-container,
    input[type="password"]::-webkit-clear-button,
    input[type="password"]::-webkit-password-reveal {
        display: none !important;
    }

    input[type="password"] {
        -webkit-appearance: none;
        appearance: none;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }

        to {
            transform: rotate(360deg);
        }
    }

    #refresh-captcha.refreshing img {
        animation: spin 0.8s linear infinite;
    }

    .glass-label {
        color: #1f2937;
        font-weight: 600;
        text-shadow: 0 1px 2px rgba(255, 255, 255, 0.8);
    }

    .glass-input::placeholder {
        color: rgba(75, 85, 99, 0.8);
    }

    .glass-logo {
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        padding: 0.75rem;
        margin-bottom: 1.5rem;
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

        .glass-button {
            padding-top: 0.6rem;
            padding-bottom: 0.6rem;
            font-size: 0.95rem;
        }

        body {
            padding-top: 6px;
            padding-bottom: 24px;
        }
    }
</style>


<body class="min-h-screen flex items-center justify-center font-sans">
    <main class="w-full max-w-md">
        <div class="glass-card rounded-3xl p-6">
            <div class="flex justify-center">
                <div class="glass-logo">
                    <img src="{{ asset('image/kayaba.webp') }}" alt="KYB" class="w-24 md:w-28 object-contain">
                </div>
            </div>
            <div class="flex justify-center -mt-2">
                <div class="text-center text-xs md:text-sm font-semibold">
                    PELAPORAN PENYIMPANGAN KUALITAS
                </div>
            </div>

            <form method="POST" action="{{ url('/login') }}" novalidate class="space-y-4">
                @csrf
                <div>
                    <label for="npk" class="glass-label block text-sm mb-1.5">NPK</label>
                    <input id="npk" type="text" name="npk" value="{{ old('npk') }}" autofocus placeholder="Masukkan NPK"
                        class="glass-input w-full rounded-xl px-4 py-2.5 text-gray-800 placeholder-gray-600 focus:outline-none transition-all duration-300"
                        required>
                    @error('npk')
                        <p class="text-red-600 text-sm mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="glass-label block text-sm mb-1.5">Password</label>
                    <div class="glass-pw-wrapper flex overflow-hidden rounded-xl transition-all duration-300">
                        <input id="password" type="password" name="password" placeholder="Masukkan password"
                            class="flex-1 bg-transparent border-0 px-4 py-2.5 text-gray-800 placeholder-gray-600 focus:outline-none"
                            required>
                        <button type="button" id="toggle-password"
                            class="backdrop-filter backdrop-blur-sm bg-white bg-opacity-20 -ml-px z-10 px-3 py-2 text-gray-700 hover:text-gray-900 focus:outline-none flex items-center justify-center rounded-r-xl transition-all duration-200">
                            <img id="eye-icon" src="{{ asset('icon/show.ico') }}" alt="show password"
                                class="w-5 h-5 object-contain">
                        </button>
                    </div>
                    @error('password')
                        <p class="text-red-600 text-sm mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="glass-label block text-sm mb-2">Captcha</label>
                    <div class="flex items-center gap-3 mb-2">
                        <img src="{{ captcha_src('flat') }}" alt="Kode captcha - klik untuk refresh" id="captcha-img"
                            class="glass-captcha rounded-lg h-12 w-auto cursor-pointer shadow-lg transition-transform duration-200 hover:scale-105">

                        <button type="button" id="refresh-captcha"
                            class="glass-refresh p-2.5 rounded-xl transition-all duration-200 flex items-center justify-center">
                            <img src="{{ asset('icon/refresh.ico') }}" alt="Refresh Captcha" class="w-4 h-4">
                        </button>
                    </div>

                    <input type="text" name="captcha" placeholder="Masukkan kode di atas"
                        class="glass-input w-full rounded-xl px-4 py-2.5 text-gray-800 placeholder-gray-600 focus:outline-none transition-all duration-300"
                        required>

                    @error('captcha')
                        <p class="text-red-600 text-sm mt-1 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="glass-button w-full mt-5 text-white py-3 rounded-xl font-semibold text-lg transition-all duration-300 transform">
                    Login
                </button>
            </form>

            <p class="text-center text-white text-xs mt-3">
                © {{ date('Y') }} PT Kayaba Indonesia
            </p>
        </div>
    </main>

    <script>
        function refreshCaptcha() {
            const img = document.getElementById('captcha-img');
            const refreshBtn = document.getElementById('refresh-captcha');
            if (!img || !refreshBtn) return;

            refreshBtn.classList.add('refreshing');

            const removeSpin = () => {
                refreshBtn.classList.remove('refreshing');
                img.removeEventListener('load', removeSpin);
            };

            img.addEventListener('load', removeSpin);
            setTimeout(removeSpin, 2000);

            img.src = '{{ captcha_src("flat") }}' + '?_=' + Date.now();
        }

        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            const showSrc = '{{ asset("icon/show.ico") }}';
            const hideSrc = '{{ asset("icon/hide.ico") }}';

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                if (eyeIcon) eyeIcon.src = hideSrc;
            } else {
                passwordInput.type = 'password';
                if (eyeIcon) eyeIcon.src = showSrc;
            }
        }

        document.getElementById('refresh-captcha')?.addEventListener('click', refreshCaptcha);
        document.getElementById('captcha-img')?.addEventListener('click', refreshCaptcha);
        document.getElementById('toggle-password')?.addEventListener('click', togglePassword);

        function toggleInputBg(input) {
            if (!input) return;

            if (input.value && input.value.trim() !== '') {
                input.style.background = 'rgba(254, 226, 226, 0.4)';
            } else {
                input.style.background = 'rgba(255, 255, 255, 0.2)';
            }
        }

        ['npk', 'password'].forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;

            toggleInputBg(el);
            el.addEventListener('input', () => toggleInputBg(el));
            el.addEventListener('blur', () => toggleInputBg(el));
        });

        const captchaInput = document.querySelector('input[name="captcha"]');
        if (captchaInput) {
            toggleInputBg(captchaInput);
            captchaInput.addEventListener('input', () => toggleInputBg(captchaInput));
            captchaInput.addEventListener('blur', () => toggleInputBg(captchaInput));
        }
    </script>
</body>

</html>