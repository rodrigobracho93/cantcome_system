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
        <div class="logo">C</div>
        <div class="header-text">
            <h1>CantCome</h1>
            <p>Cuentas por Cobrar</p>
        </div>
    </div>

    <h2 class="subtitle"><?php if($customer): ?>Cliente: <?php echo e($customer->name); ?><?php else: ?> Resumen general <?php endif; ?></h2>
    <p class="meta">Filtro de pago: <?php echo e($paymentType ? ucfirst($paymentType) : 'Todos'); ?> | Fecha: <?php echo e(now()->format('d/m/Y H:i')); ?></p>

    <?php if($sales->isEmpty()): ?>
        <p style="text-align:center;margin-top:40px;color:#9ca3af;">No se encontraron ventas</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <?php if(!$customer): ?><th>Cliente</th><?php endif; ?>
                    <th>Vendedor</th>
                    <th>Pago</th>
                    <th class="text-right">Total</th>
                    <th class="text-center">Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td class="text-gray">#<?php echo e($sale->id); ?></td>
                    <td><?php echo e($sale->created_at->format('d/m/Y')); ?></td>
                    <?php if(!$customer): ?><td><?php echo e($sale->customer?->full_name ?? 'Sin cliente'); ?></td><?php endif; ?>
                    <td><?php echo e($sale->user->name); ?></td>
                    <td><?php echo e(ucfirst($sale->payment_type)); ?></td>
                    <td class="text-right font-bold">Gs. <?php echo e(number_format($sale->total, 0, ',', '.')); ?></td>
                    <td class="text-center">
                        <?php if($sale->paid_at): ?>Cobrado
                        <?php elseif($sale->payment_type === 'contado'): ?>Pagado
                        <?php else: ?> Pendiente
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <div class="total-bar">
            <div class="summary">
                <span>Total: Gs. <?php echo e(number_format($totalGeneral, 0, ',', '.')); ?></span>
                <span class="text-emerald">Cobrado: Gs. <?php echo e(number_format($totalCobrado, 0, ',', '.')); ?></span>
                <span class="text-amber">Pendiente: Gs. <?php echo e(number_format($totalPendiente, 0, ',', '.')); ?></span>
            </div>
        </div>
    <?php endif; ?>
</body>
</html><?php /**PATH C:\xampp\Proyectos\cantcome_system\resources\views/customer-sales/pdf.blade.php ENDPATH**/ ?>