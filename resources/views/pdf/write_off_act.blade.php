<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ __('Акт на списание') }}</title>
    <style>
        body { font-family: "DejaVu Sans", sans-serif; font-size: 11px; color: #000; line-height: 1.4; margin: 40px; }
        .header { text-align: right; margin-bottom: 20px; }
        .header-left { float: left; text-align: left; }
        .header-right { float: right; text-align: right; }
        h1 { font-size: 14pt; text-align: center; margin-bottom: 20px; font-weight: bold; }
        .details { margin-bottom: 20px; }
        .details-left { float: left; }
        .details-right { float: right; }
        .clearfix::after { content: ""; clear: both; display: table; }
        p { margin-bottom: 10px; text-indent: 25px; text-align: justify;}
        table { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; vertical-align: top; }
        th { font-weight: bold; background: #f2f2f2; text-align: center; }
        .signatures { margin-top: 40px; }
        .signature-item { margin-top: 25px; }
    </style>
</head>
<body>
    <div class="header clearfix">
        <div class="header-right">
            УТВЕРЖДАЮ<br>
            Директор ПК «МУКР»<br>
            _________________ А. Дж. Чолпонова<br>
            «_____» _______________ 20___ г.
        </div>
    </div>

    <h1>АКТ № {{ $document->id }}<br>на списание материальных ценностей</h1>

    <div class="details clearfix">
        <div class="details-left">г. Бишкек</div>
        <div class="details-right">«{{ $document->created_at->format('d') }}» {{ $document->created_at->format('m') }} {{ $document->created_at->format('Y') }} г.</div>
    </div>

    <p>
        Комиссия, назначенная приказом №______ от «___» _________ 20___ г. в составе:
    </p>
    <p>
        Председатель комиссии: <strong>{{ $commission['chairman'] ?? '____________________' }}</strong><br>
        Члены комиссии: <strong>{{ $commission['members'] ?? '____________________' }}</strong>
    </p>
    <p>
        произвела осмотр нижеперечисленных материальных ценностей, предлагаемых к списанию:
    </p>

    <table>
        <thead>
            <tr>
                <th>№</th>
                <th>Наименование, марка, модель</th>
                <th>Инв. номер</th>
                <th>Кол-во</th>
                <th>Причина списания</th>
            </tr>
        </thead>
        <tbody>
            @foreach($document->assets as $index => $asset)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $asset->category->name ?? 'N/A' }} {{ $asset->serial_number }}</td>
                <td style="text-align: center;">{{ $asset->inventory_number ?? 'N/A' }}</td>
                <td style="text-align: center;">1</td>
                <td>{{ $document->reason ?? 'Не указана' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p>
        <strong>Заключение комиссии:</strong> Вышеперечисленные материальные ценности пришли в негодность и дальнейшей эксплуатации не подлежат. Списать и утилизировать.
    </p>

    <div class="signatures">
        <div class="signature-item">
            Председатель комиссии: _________________ / {{ $commission['chairman'] ?? '____________________' }} /
        </div>
        <div class="signature-item">
            Члены комиссии: _________________ / {{ $commission['members'] ?? '____________________' }} /
        </div>
        <div class="signature-item">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_________________ / ____________________ /
        </div>
    </div>

</body>
</html>
