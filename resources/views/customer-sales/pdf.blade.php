<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cuentas por Cobrar</title>
    <style>
        @page {
            margin: 20mm 15mm 15mm 25mm;
        }
        body {
            font-family: 'dejavu sans', sans-serif;
            font-size: 10px;
            color: #333;
        }
        .header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 3px solid #4f46e5;
        }
        .logo {
            width: 36px;
            height: 36px;
            background: #4f46e5;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 16px;
            font-weight: 800;
        }
        .header-text h1 {
            font-size: 18px;
            margin: 0;
            color: #111;
        }
        .header-text p {
            margin: 2px 0 0;
            font-size: 10px;
            color: #6b7280;
        }
        .subtitle {
            font-size: 13px;
            margin: 0 0 4px;
            color: #4b5563;
        }
        .meta {
            font-size: 9px;
            color: #9ca3af;
            margin: 0 0 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background: #f3f4f6;
            text-align: left;
            padding: 6px 8px;
            font-size: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e5e7eb;
            color: #6b7280;
        }
        td {
            padding: 5px 8px;
            border-bottom: 1px solid #e5e7eb;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: 700; }
        .text-gray { color: #6b7280; }
        .text-emerald { color: #059669; }
        .text-amber { color: #d97706; }
        .total-bar {
            background: #f9fafb;
            padding: 8px 12px;
            font-size: 11px;
            margin-top: 12px;
        }
        .summary {
            display: flex;
            justify-content: flex-end;
            gap: 24px;
            font-weight: 700;
        }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
        <div class="header">
        <img src="{{ public_path($systemLogo ?? 'logo.png') }}" alt="{{ $systemName ?? 'CantCome' }}" style="height: 36px; width: auto;">
        <div class="header-text">
            <h1>{{ $systemName ?? 'CantCome' }}</h1>
            <p>Cuentas por Cobrar</p>
        </div>
    </div>

    <h2 class="subtitle">@if($customer)Cliente: {{ $customer->name }}@else Resumen general @endif</h2>
    <p class="meta">Filtro de pago: {{ $paymentType ? ucfirst($paymentType) : 'Todos' }} | Fecha: {{ now()->format('d/m/Y H:i') }}</p>

    @if($sales->isEmpty())
        <p style="text-align:center;margin-top:40px;color:#9ca3af;">No se encontraron ventas</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    @if(!$customer)<th>Cliente</th>@endif
                    <th>Vendedor</th>
                    <th>Pago</th>
                    <th class="text-right">Total</th>
                    <th class="text-center">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                <tr>
                    <td class="text-gray">#{{ $sale->id }}</td>
                    <td>{{ $sale->created_at->format('d/m/Y') }}</td>
                    @if(!$customer)<td>{{ $sale->customer?->full_name ?? 'Sin cliente' }}</td>@endif
                    <td>{{ $sale->user->name }}</td>
                    <td>{{ ucfirst($sale->payment_type) }}</td>
                    <td class="text-right font-bold">Gs. {{ number_format($sale->total, 0, ',', '.') }}</td>
                    <td class="text-center">
                        @if($sale->paid_at)Cobrado
                        @elseif($sale->payment_type === 'contado')Pagado
                        @else Pendiente
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-bar">
            <div class="summary">
                <span>Total: Gs. {{ number_format($totalGeneral, 0, ',', '.') }}</span>
                <span class="text-emerald">Cobrado: Gs. {{ number_format($totalCobrado, 0, ',', '.') }}</span>
                <span class="text-amber">Pendiente: Gs. {{ number_format($totalPendiente, 0, ',', '.') }}</span>
            </div>
        </div>
    @endif
</body>
</html>