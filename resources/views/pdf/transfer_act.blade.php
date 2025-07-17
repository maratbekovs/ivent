<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ __('Акт приёма-передачи') }}</title>
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif;
            font-size: 12px;
            color: #000;
            line-height: 1.5;
            margin: 40px;
        }
        h1 {
            font-size: 16pt;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .details {
            margin-bottom: 20px;
        }
        .details-left { float: left; }
        .details-right { float: right; }
        .clearfix::after { content: ""; clear: both; display: table; }
        p {
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
            font-size: 11px;
        }
        th {
            font-weight: bold;
            background: #eee;
        }
        .signatures {
            margin-top: 40px;
        }
        .signature-block {
            width: 45%;
            display: inline-block;
        }
        .signature-block.left { float: left; }
        .signature-block.right { float: right; }
        .signature-label {
            margin-top: 40px;
            border-top: 1px solid #000;
            padding-top: 5px;
            text-align: center;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <h1>{{ __('Акт приёма-передачи') }} №{{ $movement->id }}</h1>

    <div class="details clearfix">
        <div class="details-left"><strong>г. Бишкек</strong></div>
        <div class="details-right"><strong>{{ $movement->movement_date->format('d.m.Y') }} г.</strong></div>
    </div>

    <p>
        Настоящий акт составлен о том, что 
        <strong>{{ $movement->user->name ?? 'сотрудник' }}</strong> 
        ({{ $movement->user->position ?? 'должность не указана' }}) 
        передал(а), а материально ответственное лицо принял(а) следующее имущество:
    </p>

    <table>
        <thead>
            <tr>
                <th style="width:5%;">№</th>
                <th>Наименование</th>
                <th>Инвентарный номер</th>
                <th>Серийный номер</th>
            </tr>
        </thead>
        <tbody>
            @foreach($movement->assets as $index => $asset)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $asset->category->name ?? 'N/A' }}</td>
                <td>{{ $asset->inventory_number ?? 'N/A' }}</td>
                <td>{{ $asset->serial_number ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p>
        Перемещение произведено:
        <br>
        <strong>Откуда:</strong> {{ $movement->fromLocation->name ?? 'Не указано' }}<br>
        <strong>Куда:</strong> {{ $movement->toLocation->name ?? 'Не указано' }}
    </p>

    <p>
        Техническое состояние объектов на момент передачи: <strong>{{ $movement->assets->first()->status->name ?? 'Не указано' }}</strong>.
    </p>

    @if($movement->notes)
        <p><strong>Примечания:</strong> {{ $movement->notes }}</p>
    @endif

    <div class="signatures clearfix">
        <div class="signature-block left">
            <div><strong>Сдал(а):</strong></div>
            <div class="signature-label">(подпись, Ф.И.О.)</div>
        </div>
        <div class="signature-block right">
            <div><strong>Принял(а):</strong></div>
            <div class="signature-label">(подпись, Ф.И.О.)</div>
        </div>
    </div>
</body>
</html>
