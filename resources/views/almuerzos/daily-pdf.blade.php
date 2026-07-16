<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Almuerzos {{ $fecha->format('d/m/Y') }}</title>
    <style>
        @page { margin: 10mm 10mm 10mm 25mm; }
        body { font-family: 'dejavu sans', sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 12px; }
        .header h1 { font-size: 16px; margin: 0; color: {{ $primaryColor }}; }
        .header p { margin: 2px 0; font-size: 10px; color: #666; }
        .divider { border-top: 1px dashed #ccc; margin: 8px 0; }
        .stats { display: flex; justify-content: space-around; margin: 10px 0; font-size: 11px; }
        .stats div { text-align: center; }
        .stats .num { font-size: 18px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; font-size: 10px; }
        th { background: #f3f4f6; padding: 5px 6px; text-align: left; font-size: 9px; text-transform: uppercase; color: #666; }
        td { padding: 4px 6px; border-bottom: 1px solid #f3f4f6; }
        .text-right { text-align: right; }
        .entregado { color: #059669; }
        .pendiente { color: #d97706; }
        .footer { text-align: center; margin-top: 12px; font-size: 9px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path($systemLogo ?? 'logo.png') }}" alt="{{ $systemName ?? 'CantCome' }}" style="height: 40px; width: auto; margin-bottom: 4px;">
        <h1>{{ $systemName ?? 'CantCome' }}</h1>
        <p>Almuerzos - {{ $fecha->locale('es')->isoFormat('dddd, D [de] MMMM [del] YYYY') }}</p>
    </div>

    <div class="stats">
        <div><div class="num">{{ $total }}</div>Total</div>
        <div><div class="num" style="color:#059669">{{ $entregados }}</div>Entregados</div>
        <div><div class="num" style="color:#d97706">{{ $pendientes }}</div>Pendientes</div>
    </div>

    <div class="divider"></div>

    @if($almuerzos->isEmpty())
        <p style="text-align:center;color:#999;padding:10px;">Sin personas registradas</p>
    @else
    <table>
        <thead>
            <tr>
                <th>Persona</th>
                <th class="text-right">Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($almuerzos as $a)
            <tr>
                <td>
                    {{ $a->customer->name }}
                    @if($a->observacion)
                    <div style="font-size:9px;color:#999;margin-top:1px;">{{ $a->observacion }}</div>
                    @endif
                </td>
                <td class="text-right {{ $a->entregado ? 'entregado' : 'pendiente' }}">
                    {{ $a->entregado ? '✅ Entregado' . ($a->entregado_at ? ' ' . $a->entregado_at->format('H:i') : '') : '⏳ Pendiente' }}
                </td>
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
