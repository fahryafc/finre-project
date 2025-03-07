<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email Verification</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .email-header {
            background-color: #307487;
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }
        .email-header h1 {
            margin: 0;
            font-size: 28px;
        }
        .email-body {
            padding: 20px 30px;
            line-height: 1.6;
            color: #333333;
        }
        .email-body p {
            margin: 15px 0;
            font-size: 16px;
        }
        .email-footer {
            text-align: center;
            padding: 15px;
            font-size: 14px;
            color: #555555;
            border-top: 1px solid #ddd;
            background-color: #f9f9f9;
        }
        .btn {
            display: inline-block;
            margin: 20px 0;
            padding: 12px 25px;
            background-color: #307487;
            color: #ffffff !important;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #255a65;
        }
        a {
            color: #307487;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Verifikasi Email Anda</h1>
        </div>
        <div class="email-body">
            <p>Terima kasih telah mendaftar di Finre.</p>
            <p>Untuk mengaktifkan akun Anda, silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda:</p>
            <a href="{{ $url }}" class="btn">Click to Verify</a>
            <p>Atau, Anda dapat menggunakan link berikut jika tombol tidak berfungsi:</p>
            <p><a href="{{ $url }}">{{ $url }}</a></p>
            <p>Jika Anda tidak melakukan pendaftaran ini, cukup abaikan email ini.</p>
            <p>Terima kasih,</p>
            <p><a href="https://finre.id/">Finre.id</a></p>
        </div>
        <div class="email-footer">
            &copy; <?= date('Y'); ?> Finre. All rights reserved.
        </div>
    </div>
</body>
</html>
