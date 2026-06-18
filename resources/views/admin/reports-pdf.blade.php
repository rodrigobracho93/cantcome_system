<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reportes</title>
    <style>
        @page { margin: 15mm 15mm 15mm 25mm; }
        body { font-family: 'dejavu sans', sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 15px; }
        .header h1 { font-size: 18px; margin: 0; color: {{ $primaryColor }}; }
        .header p { margin: 2px 0; font-size: 10px; color: #666; }
        .header .date { font-size: 10px; color: #999; }
        .divider { border-top: 1px solid #e5e7eb; margin: 12px 0; }
        .summary { display: flex; justify-content: space-between; margin-bottom: 15px; }
        .summary .card { text-align: center; padding: 8px 12px; background: #f9fafb; border-radius: 6px; flex: 1; margin: 0 4px; }
        .summary .card .label { font-size: 9px; text-transform: uppercase; color: #666; }
        .summary .card .value { font-size: 14px; font-weight: bold; color: #333; margin-top: 2px; }
        table { width: 100%; border-collapse: collapse; font-size: 10px; }
        th { background: #f3f4f6; padding: 6px 8px; text-align: left; font-size: 9px; text-transform: uppercase; color: #666; }
        td { padding: 5px 8px; border-bottom: 1px solid #f3f4f6; }
        .text-right { text-align: right; }
        .footer { text-align: center; margin-top: 15px; font-size: 9px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('logo.png') }}" alt="CantCome" style="height: 45px; width: auto; margin-bottom: 6px;">
        <h1>CantCome</h1>
        <p>Cantina & Cafetería - Reportes</p>
        <p class="date">Generado el {{ now()->locale('es')->isoFormat('D [de] MMMM [del] YYYY') }}</p>
    </div>

    <div class="divider"></div>

    <div class="summary">
        <div class="card">
            <div class="label">Ventas Totales</div>
            <div class="value">{{ $totalSales }}</div>
        </div>
        <div class="card">
            <div class="label">Ingresos Totales</div>
            <div class="value">Gs. {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        </div>
        <div class="card">
            <div class="label">Ingresos del Mes</div>
            <div class="value">Gs. {{ number_format($monthlyRevenue, 0, ',', '.') }}</div>
        </div>
        <div class="card">
            <div class="label">Clientes</div>
            <div class="value">{{ $totalCustomers }}</div>
        </div>
    </div>

    <div class="divider"></div>

    <h3 style="font-size: 12px; margin: 0 0 8px 0;">Ventas Diarias (30 días)</h3>

    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th class="text-right">Ventas</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dailySales as $sale)
            <tr>
                <td>{{ \Carbon\Carbon::parse($sale->date)->format('d/m/Y') }}</td>
                <td class="text-right">{{ $sale->count }}</td>
                <td class="text-right">Gs. {{ number_format($sale->total, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            @if($dailySales->isEmpty())
            <tr>
                <td colspan="3" style="text-align: center; padding: 15px; color: #999;">Sin datos de ventas</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>CantCome - Sistema de Gestión</p>
    </div>
</body>
</html>