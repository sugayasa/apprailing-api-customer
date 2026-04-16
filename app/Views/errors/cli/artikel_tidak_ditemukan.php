<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artikel Tidak Ditemukan - Rich Railing</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 50%, #1a1a1a 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 200px;
            background: linear-gradient(to top, rgba(218, 165, 32, 0.3) 0%, transparent 100%);
            pointer-events: none;
        }

        .container {
            text-align: center;
            padding: 40px 20px;
            max-width: 600px;
            z-index: 1;
        }

        .icon-container {
            margin-bottom: 30px;
            animation: fadeInUp 0.6s ease-out 0.2s both;
        }

        .icon {
            width: 120px;
            height: 120px;
            margin: 0 auto;
            background: rgba(218, 165, 32, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid rgba(218, 165, 32, 0.3);
        }

        .icon svg {
            width: 60px;
            height: 60px;
            stroke: #DAA520;
        }

        .message {
            animation: fadeInUp 0.6s ease-out 0.4s both;
        }

        .message h1 {
            font-size: 24px;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 15px;
            line-height: 1.4;
        }

        .message p {
            font-size: 16px;
            color: #b0b0b0;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 480px) {

            .message h1 {
                font-size: 20px;
            }

            .message p {
                font-size: 14px;
            }

            .icon {
                width: 100px;
                height: 100px;
            }

            .icon svg {
                width: 50px;
                height: 50px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon-container">
            <div class="icon">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
            </div>
        </div>

        <div class="message">
            <h1>Artikel yang Anda Cari<br>Tidak Ditemukan</h1>
            <p>Maaf, artikel yang Anda cari tidak tersedia atau telah dihapus.<br>Silakan kembali ke halaman utama.</p>
        </div>
    </div>
</body>
</html>
