<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Buku Tabungan {{ $savingsBook->book_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #1e293b;
            background: #fff;
        }

        /* ── Header ── */
        .page-header {
            background: #2563eb; /* blue-600 */
            color: #fff;
            padding: 20px 28px;
            margin-bottom: 16px;
        }
        .header-inner { display: flex; justify-content: space-between; align-items: center; }
        .bank-name    { font-size: 18px; font-weight: 700; letter-spacing: .02em; }
        .bank-sub     { font-size: 8px; opacity: .75; margin-top: 3px; }
        .doc-right    { text-align: right; }
        .doc-label    { font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; opacity: .8; }
        .doc-number   { font-family: 'Courier New', monospace; font-size: 15px; font-weight: 700; letter-spacing: .08em; margin-top: 3px; }

        /* ── Info nasabah ── */
        .info-bar {
            display: flex;
            gap: 0;
            margin: 0 28px 16px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }
        .info-cell {
            flex: 1;
            padding: 10px 14px;
            border-right: 1px solid #e2e8f0;
        }
        .info-cell:last-child { border-right: none; }
        .info-cell .lbl {
            font-size: 7px; font-weight: 700; text-transform: uppercase;
            letter-spacing: .07em; color: #94a3b8; margin-bottom: 3px;
        }
        .info-cell .val { font-size: 10px; font-weight: 700; color: #1e293b; }
        .info-cell .val.mono { font-family: 'Courier New', monospace; font-size: 11px; }
        .info-cell .val.balance { font-size: 13px; color: #2563eb; }

        /* ── Section title ── */
        .sec-title {
            margin: 0 28px 8px;
            font-size: 8px; font-weight: 700; text-transform: uppercase;
            letter-spacing: .08em; color: #64748b;
        }

        /* ── Table ── */
        .tbl-wrap { margin: 0 28px; }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            background: #1e293b; /* black/dark */
            color: #fff;
            padding: 7px 10px;
            text-align: left;
            font-size: 8px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .05em;
        }
        thead th.r { text-align: right; }

        tbody tr                   { border-bottom: 1px solid #f1f5f9; }
        tbody tr:nth-child(even)   { background: #f8fafc; }
        tbody tr:last-child        { border-bottom: none; }

        tbody td          { padding: 6px 10px; font-size: 9px; vertical-align: middle; }
        tbody td.center   { text-align: center; color: #94a3b8; }
        tbody td.date     { color: #64748b; white-space: nowrap; }
        tbody td.desc     { font-weight: 600; }
        tbody td.ref      { color: #94a3b8; font-size: 8px; font-family: 'Courier New', monospace; }
        tbody td.debit    { text-align: right; font-family: 'Courier New', monospace; color: #16a34a; font-weight: 700; }
        tbody td.credit   { text-align: right; font-family: 'Courier New', monospace; color: #dc2626; font-weight: 700; }
        tbody td.zero     { text-align: right; color: #cbd5e1; }
        tbody td.balance  { text-align: right; font-family: 'Courier New', monospace; color: #2563eb; font-weight: 700; }

        /* ── Footer ── */
        .page-footer {
            margin-top: 20px;
            padding: 10px 28px;
            border-top: 1px solid #e2e8f0;
            display: flex; justify-content: space-between;
            font-size: 8px; color: #94a3b8;
        }

        /* ── Signature ── */
        .sig-wrap { display: flex; justify-content: flex-end; padding: 0 28px; margin-top: 24px; }
        .sig-box  {
            text-align: center; width: 140px;
            border-top: 1px solid #1e293b;
            padding-top: 5px; margin-top: 52px;
            font-size: 8px; color: #64748b;
        }
        .sig-box .sig-name { font-weight: 700; color: #1e293b; }

        /* ── Empty ── */
        .no-data { text-align: center; padding: 32px; color: #94a3b8; font-size: 10px; }
    </style>
</head>
<body>

    {{-- Header --}}
    <div class="page-header">
        <div class="header-inner">
            <div>
                <div class="bank-name">🏦 INB Bank</div>
                <div class="bank-sub">Indonesia National Banking · Buku Tabungan Digital</div>
            </div>
            <div class="doc-right">
                <div class="doc-label">Nomor Buku</div>
                <div class="doc-number">{{ $savingsBook->book_number }}</div>
            </div>
        </div>
    </div>

    {{-- Info Bar --}}
    <div class="info-bar">
        <div class="info-cell">
            <div class="lbl">Nama Nasabah</div>
            <div class="val">{{ $savingsBook->account->user->name }}</div>
        </div>
        <div class="info-cell">
            <div class="lbl">No. Rekening</div>
            <div class="val mono">{{ $savingsBook->account->account_number }}</div>
        </div>
        <div class="info-cell">
            <div class="lbl">Jenis Rekening</div>
            <div class="val">{{ ucfirst($savingsBook->account->account_type) }}</div>
        </div>
        <div class="info-cell">
            <div class="lbl">Tgl Cetak</div>
            <div class="val">{{ now()->format('d M Y, H:i') }}</div>
        </div>
        <div class="info-cell">
            <div class="lbl">Saldo Saat Ini</div>
            <div class="val balance">IDR {{ number_format($savingsBook->account->balance, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- Section title --}}
    <div class="sec-title">Riwayat Mutasi Buku Tabungan</div>

    {{-- Table --}}
    <div class="tbl-wrap">
        @if($entries->isEmpty())
            <div class="no-data">Belum ada mutasi yang tersimpan di buku tabungan ini.</div>
        @else
        <table>
            <thead>
                <tr>
                    <th style="width:20px">#</th>
                    <th style="width:60px">Tanggal</th>
                    <th>Keterangan</th>
                    <th style="width:80px">No. Ref</th>
                    <th class="r" style="width:90px">Debit (Masuk)</th>
                    <th class="r" style="width:90px">Kredit (Keluar)</th>
                    <th class="r" style="width:95px">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($entries as $i => $entry)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td class="date">{{ \Carbon\Carbon::parse($entry->entry_date)->format('d/m/Y') }}</td>
                    <td class="desc">{{ $entry->description }}</td>
                    <td class="ref">{{ $entry->transaction->reference_code ?? '-' }}</td>
                    <td class="{{ $entry->debit > 0 ? 'debit' : 'zero' }}">
                        {{ $entry->debit > 0 ? number_format($entry->debit, 0, ',', '.') : '—' }}
                    </td>
                    <td class="{{ $entry->credit > 0 ? 'credit' : 'zero' }}">
                        {{ $entry->credit > 0 ? number_format($entry->credit, 0, ',', '.') : '—' }}
                    </td>
                    <td class="balance">{{ number_format($entry->balance, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    {{-- Signature --}}
    <div class="sig-wrap">
        <div>
            <div style="font-size:8px; color:#64748b; text-align:center; margin-bottom:2px;">
                Palembang, {{ now()->format('d M Y') }}
            </div>
            <div class="sig-box">
                <div class="sig-name">Petugas Bank</div>
                <div>(................................................)</div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="page-footer">
        <span>Dicetak otomatis oleh sistem INB Bank · Bukan merupakan dokumen resmi tanpa tanda tangan</span>
        <span>{{ $savingsBook->book_number }} · {{ now()->format('d/m/Y H:i:s') }}</span>
    </div>

</body>
</html>