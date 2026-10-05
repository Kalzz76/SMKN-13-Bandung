<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — {{ $pengaturan->nama_sekolah ?? 'SMKN 13 Bandung' }}</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap');

        :root {
            --primary-color: #3b5d50;
            --secondary-color: #4b7463;
            --black: #000000;
            --white: #ffffff;
            --gray: #efefef;
            --gray-2: #757575;

            --facebook-color: #4267B2;
            --google-color: #DB4437;
            --twitter-color: #1DA1F2;
            --insta-color: #E1306C;
        }

        * {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100vh;
            overflow: hidden;
        }

        .container {
            position: relative;
            min-height: 100vh;
            overflow: hidden;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            height: 100vh;
        }

        .col {
            width: 50%;
        }

        .align-items-center {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .form-wrapper {
            width: 100%;
            max-width: 28rem;
        }

        .form {
            padding: 2rem 1.8rem;
            background-color: var(--white);
            border-radius: 1.5rem;
            width: 100%;
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
            transform: scale(1);
            transition: .5s ease-in-out;
        }

        .input-group {
            position: relative;
            width: 100%;
            margin: 1rem 0;
        }

        .input-group i {
            position: absolute;
            top: 50%;
            left: 1rem;
            transform: translateY(-50%);
            font-size: 1.4rem;
            color: var(--gray-2);
        }

        .input-group input {
            width: 100%;
            padding: 1rem 3rem;
            font-size: 1rem;
            background-color: var(--gray);
            border-radius: .5rem;
            border: 0.125rem solid var(--white);
            outline: none;
            transition: border 0.2s ease;
        }

        .input-group input:focus {
            border: 0.125rem solid var(--primary-color);
        }

        .form button {
            cursor: pointer;
            width: 100%;
            padding: .75rem 0;
            border-radius: .5rem;
            border: none;
            background-color: var(--primary-color);
            color: var(--white);
            font-size: 1.15rem;
            font-weight: 600;
            outline: none;
            transition: background-color 0.3s ease;
        }

        .form button:hover {
            background-color: #314d43;
        }

        .form .pesan {
            display: none;
            margin-bottom: .8rem;
            padding: .6rem .8rem;
            border-radius: .5rem;
            font-size: .8rem;
            text-align: left;
            line-height: 1.4;
        }

        .form .pesan.gagal {
            display: block;
            background-color: #fdecea;
            color: #a12622;
            border: 1px solid #f5c2c0;
        }

        .form .pesan.berhasil {
            display: block;
            background-color: #e8f3ee;
            color: #24614c;
            border: 1px solid #b7ddcb;
        }

        .form button:disabled {
            opacity: .7;
            cursor: not-allowed;
        }

        .form .tautan-beranda {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .form p {
            margin: 1rem 0 0.5rem 0;
            font-size: .85rem;
        }

        .flex-col {
            flex-direction: column;
        }

        .pointer {
            cursor: pointer;
        }

        .content-row {
            position: absolute;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 6;
            width: 100%;
        }

        .text {
            margin: 4rem;
            color: var(--white);
        }

        .text h2 {
            font-size: 3.5rem;
            font-weight: 800;
            margin: 2rem 0 1rem 0;
            transition: 1s ease-in-out;
            line-height: 1.2;
        }

        .text p {
            font-weight: 500;
            font-size: 1.15rem;
            line-height: 1.6;
            transition: 1s ease-in-out;
            transition-delay: .2s;
            color: rgba(255, 255, 255, 0.9);
            max-width: 420px;
        }

        .text.sign-in h2,
        .text.sign-in p {
            transform: translateX(0);
        }

        .container::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            height: 100vh;
            width: 300vw;
            transform: translate(35%, 0);
            background-image: linear-gradient(-45deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            transition: 1s ease-in-out;
            z-index: 6;
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
            border-bottom-right-radius: max(50vw, 50vh);
            border-top-left-radius: max(50vw, 50vh);
        }

        .container.sign-in::before {
            transform: translate(0, 0);
            right: 50%;
        }

        @media only screen and (max-width: 860px) {
            .container::before,
            .container.sign-in::before {
                height: 100vh;
                border-bottom-right-radius: 0;
                border-top-left-radius: 0;
                z-index: 0;
                transform: none;
                right: 0;
            }

            .content-row {
                display: none;
            }

            .spacer-col {
                display: none !important;
            }

            .col {
                width: 100%;
                position: relative;
                padding: 1.5rem;
                background-color: transparent;
                transform: none;
            }

            .row {
                align-items: center;
                justify-content: center;
            }

            .form {
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
            }
        }
    </style>
</head>
<body>
    <div id="container" class="container sign-in">
        <div class="row">
            <div class="col spacer-col"></div>
            <div class="col align-items-center flex-col sign-in">
                <div class="form-wrapper align-items-center">
                    <div class="form sign-in">
                        <div style="text-align: center; margin-bottom: 20px;">
                            <a href="{{ url('/') }}" style="text-decoration: none; display: inline-flex; align-items: center; gap: 10px;">
                                @if($pengaturan && $pengaturan->logo)
                                    <img src="{{ asset('storage/' . $pengaturan->logo) }}" alt="Logo {{ $pengaturan->nama_sekolah }}" style="height: 38px; object-fit: contain;">
                                @else
                                    <div style="background: #3b5d50; color: #ffffff; width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 18px;">13</div>
                                @endif
                                <span style="font-size: 24px; font-weight: 800; color: #3b5d50; letter-spacing: -0.5px;">{{ $pengaturan->nama_sekolah ?? 'SMKN 13 Bandung' }}<span style="color: #f9bf29;">.</span></span>
                            </a>
                        </div>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="input-group">
                                <i class='bx bxs-user'></i>
                                <input type="text" name="username" placeholder="Username / NIP" required autocomplete="username" autofocus value="{{ old('username') }}">
                            </div>
                            <div class="input-group">
                                <i class='bx bxs-lock-alt'></i>
                                <input type="password" name="password" placeholder="Password" required autocomplete="current-password">
                            </div>

                            @if(session('error'))
                                <div class="pesan gagal">
                                    {{ session('error') }}
                                </div>
                            @endif
                            @if(session('sukses'))
                                <div class="pesan berhasil">
                                    {{ session('sukses') }}
                                </div>
                            @endif
                            @if($errors->any())
                                <div class="pesan gagal">
                                    {{ $errors->first() }}
                                </div>
                            @endif

                            <button type="submit" id="tombolMasuk">
                                Masuk
                            </button>
                        </form>



                        <p>
                            <a class="tautan-beranda" href="{{ url('/') }}">
                                <i class='bx bx-home-alt'></i> Kembali ke Beranda
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row content-row">
            <div class="col align-items-center flex-col">
                <div class="text sign-in">
                    <h2>
                        Selamat datang
                    </h2>
                    <p>
                        Sistem Informasi & Portal Akademik {{ $pengaturan->nama_sekolah ?? 'SMKN 13 Bandung' }}
                    </p>
                </div>
                <div class="img sign-in">
                </div>
            </div>
        </div>
    </div>
</body>
</html>
