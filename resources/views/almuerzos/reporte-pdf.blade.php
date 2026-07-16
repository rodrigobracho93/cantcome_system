<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte Almuerzos {{ \Carbon\Carbon::create()->month($mes)->locale('es')->monthName }} {{ $anio }}</title>
    <style>
        @page { margin: 15mm 15mm 15mm 25mm; }
        body { font-family: 'dejavu sans', sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 15px; }
        .header h1 { font-size: 18px; margin: 0; color: {{ $primaryColor }}; }
        .header p { margin: 2px 0; font-size: 10px; color: #666; }
        .divider { border-top: 1px solid #e5e7eb; margin: 12px 0; }
        .total-card { text-align: center; margin: 15px 0; padding: 10px; background: #f9fafb; border-radius: 6px; }
        .total-card .number { font-size: 28px; font-weight: bold; color: {{ $primaryColor }}; }
        .total-card .label { font-size: 10px; text-transform: uppercase; color: #666; }
        table { width: 100%; border-collapse: collapse; font-size: 10px; margin-bottom: 15px; }
        th { background: #f3f4f6; padding: 6px 8px; text-align: left; font-size: 9px; text-transform: uppercase; color: #666; }
        td { padding: 5px 8px; border-bottom: 1px solid #f3f4f6; }
        .text-right { text-align: right; }
        .section-title { font-size: 12px; font-weight: bold; margin: 15px 0 8px 0; }
        .footer { text-align: center; margin-top: 15px; font-size: 9px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path($systemLogo ?? 'logo.png') }}" alt="{{ $systemName ?? 'CantCome' }}" style="height: 45px; width: auto; margin-bottom: 6px;">
        <h1>{{ $systemName ?? 'CantCome' }}</h1>
        <p>Almuerzos - Reporte Mensual</p>
        <p>{{ \Carbon\Carbon::create()->month($mes)->locale('es')->monthName }} {{ $anio }}</p>
    </div>

    <div class="divider"></div>

    <div class="total-card">
        <div class="number">{{ $totalPlatos }}</div>
        <div class="label">Total de platos entregados</div>
    </div>

    <div class="section-title">Platos por Día</div>
    @if($porDia->isEmpty())
        <p style="text-align:center;color:#999;padding:10px;">Sin entregas este mes</p>
    @else
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th class="text-right">Platos</th>
            </tr>
        </thead>
        <tbody>
            @foreach($porDia as $dia)
            <tr>
                <td>{{ $dia['fecha']->locale('es')->isoFormat('dddd D [de] MMM') }}</td>
                <td class="text-right">{{ $dia['cantidad'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="section-title">Platos por Cliente</div>
    @if($porCliente->isEmpty())
        <p style="text-align:center;color:#999;padding:10px;">Sin entregas este mes</p>
    @else
    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th class="text-right">Platos</th>
            </tr>
        </thead>
        <tbody>
            @foreach($porCliente as $cliente)
            <tr>
                <td>{{ $cliente['customer']->name }}</td>
                <td class="text-right">{{ $cliente['cantidad'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif

    <div class="footer">
        <p>{{ $systemName ?? 'CantCome' }} - Sistema de Gestión</p>
    </div>
</body>
</html>
