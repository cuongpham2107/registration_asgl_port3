<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Phiếu khai thác</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 500px;
            height: auto;
            border: 1px dashed #4a5568;
            padding: 6px;
        }

        /* Use table layout for PDF compatibility instead of flex */
        .header-section {
            display: table;
            width: 100%;
        }

        .logo-container {
            display: table-cell;
            vertical-align: middle;
            width: 70px;
            padding-right: 12px;
        }

        .logo-container img {
            max-width: 60px;
        }

        .company-info {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }


        .company-name {
            font-weight: bold;
            font-size: 16px;
            margin: 0;
            padding: 0;
        }

        .company-underline {
            display: inline-block;
            width: 33%;
            border-bottom: 1px solid #000;
            height: 1px;
            margin: 2px 0;
        }

        .title {
            font-size: 13px;
            margin: 0;
            padding: 0;
        }

        .invoice-number {
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .content-section {
            display: block;
        }

        .field-row {
            display: block;
            margin-bottom: 4px;
        }

        /* Two-column layout for label and value using inline-blocks */
        .field-label {
            display: inline-block;
            vertical-align: top;
            font-weight: bold;
        }

        .field-value {
            display: inline-block;
            vertical-align: top;
            border-bottom: 1px dotted #000;
            padding-bottom: 2px;
        }

        .field-label {
            font-weight: bold;
        }

        .field-value {
            text-decoration: underline;
            text-decoration-style: dotted;
            text-underline-offset: 2px;
        }

        .checkbox-row {
            display: block;
            margin-top: 4px;
            font-weight: bold;
        }

        .checkbox-inline {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 1px solid #000;
            text-align: center;
            line-height: 16px;
            font-size: 10px;
            margin: 0 6px;
            vertical-align: middle;
        }

        .tax-note {
            text-align: center;
            font-size: 11px;
            margin-top: 8px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header-section">
            <div class="logo-container">
                <img src="{{ public_path('images/ASG.png') }}" alt="logo" />
            </div>
            <div class="company-info">
                <p class="company-name">CÔNG TY CỔ PHẦN LOGISTICS ASG</p>
                <p class="company-underline"></p>
                <p class="title">PHÍ KHAI THÁC</p>
                <p class="invoice-number">Số: 2025-11.5 <span
                        style="margin-left: 32px;">{{ str_pad($record['id'] ?? '00321', 5, '0', STR_PAD_LEFT) }}</span>
                </p>
            </div>
        </div>

        <div class="content-section">
            <div class="field-row">
                <span class="field-label">Xe ô tô, BKS:</span>
                <span class="field-value">{{ $record['license_plate'] ?? '15C-35515' }}</span>
            </div>

            <div class="field-row">
                <span class="field-label">Tải trọng:</span>
                <span
                    class="field-value">{{ $record['load_capacity']['name'] ?? "Xe chở container 40'-45', xe trên 10 tấn, xe rơ móoc" }}</span>
            </div>

            <div class="field-row">
                <span class="field-label">Giờ vào:</span>
                <span class="field-value">{{ $record['start_date'] ? \Carbon\Carbon::parse($record['start_date'])->format('H') : '15' }} giờ / {{ $record['start_date'] ? \Carbon\Carbon::parse($record['start_date'])->format('i') : '30' }} phút</span>
                <span style="margin-left: 12px;">Ngày:</span>
                <span class="field-value">{{ $record['start_date'] ? \Carbon\Carbon::parse($record['start_date'])->format('d / m / Y') : '18 / 12 / 2025' }}</span>
            </div>

            <div class="field-row">
                <span class="field-label">Giờ ra:</span>
                <span class="field-value">{{ $record['end_date'] ? \Carbon\Carbon::parse($record['end_date'])->format('H') : '20' }} giờ / {{ $record['end_date'] ? \Carbon\Carbon::parse($record['end_date'])->format('i') : '30' }} phút</span>
                <span style="margin-left: 12px;">Ngày:</span>
                <span class="field-value">{{ $record['end_date'] ? \Carbon\Carbon::parse($record['end_date'])->format('d / m / Y') : '18 / 12 / 2025' }}</span>
            </div>


            <div class="field-row">
                <span class="field-label">Tổng thời gian khai thác:</span>
                <span class="field-value">{{ $record['total_hours'] ?? 0 }} Giờ</span>
            </div>

            <div class="checkbox-row">
                <span>Vé ngày: {{ number_format($record['load_capacity']['daytime_price'] ?? 0) }} đồng</span>
                <span class="checkbox-inline">{{ ($record['is_daytime'] ?? true) ? 'x' : '' }}</span>
                <span style="margin-left: 12px;">Vé đêm: {{ number_format($record['load_capacity']['nighttime_price'] ?? 0) }} đồng</span>
                <span class="checkbox-inline">{{ ($record['is_daytime'] ?? true) ? '' : 'x' }}</span>
            </div>
            <div class="tax-note">(Đã bao gồm thuế GTGT)</div>
        </div>
    </div>

   
</body>

</html>