<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .header { background-color: #000000; padding: 40px 20px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; letter-spacing: 2px; }
        .header span { color: #3b82f6; } /* Blue color for 'Bank' */
        .content { padding: 40px 30px; color: #333333; line-height: 1.6; }
        .footer { padding: 20px; text-align: center; color: #999999; font-size: 12px; background: #f9f9f9; }
        .button { display: inline-block; padding: 14px 30px; background-color: #3b82f6; color: #ffffff !important; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 20px; }
        .nik-box { background: #f3f4f6; padding: 10px; border-radius: 8px; font-family: monospace; font-size: 14px; margin-top: 10px; display: inline-block; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Indonesia National<span>BANK</span></h1>
        </div>

        <div class="content">
            <h2 style="margin-top: 0;">Halo, {{ $user->name }}!</h2>
            <p>Terima kasih telah memilih <strong>IBN Premium</strong> sebagai partner finansial Anda. Satu langkah lagi untuk mengaktifkan akun perbankan digital Anda.</p>
            
            <p>Berikut adalah ringkasan pendaftaran Anda:</p>
            <div class="nik-box">
                NIK: {{ substr($user->nik, 0, 4) }}-xxxx-xxxx-{{ substr($user->nik, -4) }}
            </div>

            <p style="margin-top: 25px;">Silakan klik tombol di bawah ini untuk memverifikasi alamat email Anda:</p>
            
            <div style="text-align: center;">
                <a href="{{ $url }}" class="button">Verifikasi Email Saya</a>
            </div>

            <p style="margin-top: 30px; font-size: 13px; color: #666;">
                Jika tombol di atas tidak berfungsi, salin dan tempel URL berikut ke browser Anda:<br>
                <a href="{{ $url }}" style="color: #3b82f6; word-break: break-all;">{{ $url }}</a>
            </p>
        </div>

        <div class="footer">
            <p>&copy; 2026 IBN Premium. Seluruh Hak Cipta Dilindungi.<br>
            Ini adalah email otomatis, mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>