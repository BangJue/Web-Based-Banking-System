<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Transaksi - {{ $transaction->reference_code ?? $transaction->id }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #eee;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0056b3;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .bank-name {
            font-size: 24px;
            font-weight: bold;
            color: #0056b3;
            text-transform: uppercase;
        }
        .receipt-title {
            font-size: 14px;
            color: #777;
            margin-top: 5px;
        }
        .status-badge {
            text-align: center;
            margin-bottom: 20px;
        }
        .status {
            display: inline-block;
            padding: 5px 15px;
            background: #e6f4ea;
            color: #1e7e34;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .amount-box {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .amount-label {
            font-size: 12px;
            color: #888;
            text-transform: uppercase;
            display: block;
        }
        .amount-value {
            font-size: 28px;
            font-weight: bold;
            color: #000;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
        }
        .details-table td {
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
            font-size: 13px;
        }
        .label {
            color: #888;
            width: 40%;
        }
        .value {
            font-weight: bold;
            text-align: right;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            color: #aaa;
        }
        .transfer-info {
            background: #f0f7ff;
            padding: 10px;
            border-radius: 8px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="bank-name">Indonesia National Bank</div>
            <div class="receipt-title">Bukti Transaksi Elektronik</div>
        </div>

        <div class="status-badge">
            <span class="status">Berhasil</span>
        </div>

        <div class="amount-box">
            <span class="amount-label">Nominal Transaksi</span>
            <span class="amount-value">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
        </div>

        <table class="details-table">
            <tr>
                <td class="label">Tanggal & Waktu</td>
                <td class="value">{{ $transaction->created_at->format('d M Y, H:i:s') }} WIB</td>
            </tr>
            <tr>
                <td class="label">Nomor Referensi</td>
                <td class="value">{{ $transaction->reference_code ?? 'TXN-'.$transaction->id }}</td>
            </tr>
            <tr>
                <td class="label">Jenis Transaksi</td>
                <td class="value">{{ strtoupper(str_replace('_', ' ', $transaction->type)) }}</td>
            </tr>
            <tr>
                <td class="label">Sumber Rekening</td>
                <td class="value">{{ $transaction->account->account_number }}</td>
            </tr>

            {{-- Jika Transaksi Transfer --}}
            @if($transaction->transfer)
            <tr>
                <td colspan="2">
                    <div class="transfer-info">
                        <table style="width: 100%">
                            <tr>
                                <td style="border:none; color:#0056b3"><strong>Pengirim:</strong> {{ $transaction->transfer->fromAccount->user->name }}</td>
                                <td style="border:none; text-align:right; color:#0056b3"><strong>Penerima:</strong> {{ $transaction->transfer->toAccount->user->name }}</td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="label">Metode Transfer</td>
                <td class="value">{{ strtoupper($transaction->transfer->method) }}</td>
            </tr>
            <tr>
                <td class="label">Biaya Admin</td>
                <td class="value">Rp {{ number_format($transaction->transfer->admin_fee, 0, ',', '.') }}</td>
            </tr>
            @endif

            @if($transaction->description)
            <tr>
                <td class="label">Catatan</td>
                <td class="value">{{ $transaction->description }}</td>
            </tr>
            @endif
        </table>

        <div class="footer">
            <p>Dokumen ini diterbitkan secara otomatis oleh sistem Indonesia National Bank (INB) dan merupakan bukti transaksi yang sah.</p>
            <p>&copy; {{ date('Y') }} Indonesia National Bank. Terdaftar dan diawasi oleh OJK.</p>
        </div>
    </div>
</body>
</html>