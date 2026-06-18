<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recibo #<?php echo e($sale->id); ?></title>
    <style>
        @page { margin: 10mm; }
        body { font-family: 'dejavu sans', sans-serif; font-size: 11px; color: #333; }
        .header { text-align: center; margin-bottom: 8px; }
        .header h1 { font-size: 16px; margin: 0; color: #4f46e5; }
        .header p { margin: 2px 0; font-size: 10px; color: #666; }
        .divider { border-top: 1px dashed #ccc; margin: 8px 0; }
        .info { margin-bottom: 8px; font-size: 10px; }
        .info span { color: #666; }
        table { width: 100%; border-collapse: collapse; font-size: 10px; }
        th { background: #f3f4f6; padding: 4px 6px; text-align: left; font-size: 9px; text-transform: uppercase; color: #666; }
        td { padding: 4px 6px; border-bottom: 1px solid #f3f4f6; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .totals { margin-top: 8px; }
        .totals div { display: flex; justify-content: space-between; padding: 2px 6px; font-size: 10px; }
        .totals .grand-total { border-top: 2px solid #333; margin-top: 4px; padding-top: 4px; font-weight: bold; font-size: 12px; }
        .footer { text-align: center; margin-top: 12px; font-size: 9px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <img src="<?php echo e(public_path('logo.png')); ?>" alt="Logo" style="width: 60px; height: auto; margin-bottom: 4px;">
        <h1>CantCome</h1>
        <p>Cantina & Comedor</p>
        <p>Recibo #<?php echo e($sale->id); ?></p>
        <p><?php echo e($sale->created_at->locale('es')->isoFormat('D [de] MMM [del] YYYY - H:mm')); ?></p>
    </div>

    <div class="divider"></div>

    <div class="info">
        <div><span>Vendedor:</span> <?php echo e($sale->user->name); ?></div>
        <div><span>Cliente:</span> <?php echo e($sale->customer?->name ?? 'Consumidor Final'); ?></div>
        <?php if($sale->customer?->document): ?>
        <div><span>Cédula/RUC:</span> <?php echo e($sale->customer->document); ?></div>
        <?php endif; ?>
        <div><span>Pago:</span> <?php echo e($sale->payment_type === 'contado' ? 'Contado' : 'Crédito'); ?></div>
    </div>

    <div class="divider"></div>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th class="text-center">Cant.</th>
                <th class="text-right">Precio</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $sale->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($item->product->name); ?></td>
                <td class="text-center"><?php echo e($item->quantity); ?></td>
                <td class="text-right">Gs. <?php echo e(number_format($item->unit_price, 0, ',', '.')); ?></td>
                <td class="text-right">Gs. <?php echo e(number_format($item->subtotal, 0, ',', '.')); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div class="totals">
        <div><span>Subtotal</span><span>Gs. <?php echo e(number_format($sale->subtotal, 0, ',', '.')); ?></span></div>
        <div><span>IVA (10%)</span><span>Gs. <?php echo e(number_format($sale->tax, 0, ',', '.')); ?></span></div>
        <div class="grand-total"><span>Total</span><span>Gs. <?php echo e(number_format($sale->total, 0, ',', '.')); ?></span></div>
    </div>

    <?php if($sale->notes): ?>
    <div class="divider"></div>
    <div class="info"><span>Notas:</span> <?php echo e($sale->notes); ?></div>
    <?php endif; ?>

    <div class="footer">
        <p>¡Gracias por su compra!</p>
        <p>CantCome - Sistema de Gestión</p>
    </div>
</body>
</html><?php /**PATH C:\xampp\Proyectos\cantcome_system\resources\views/sales/recibo.blade.php ENDPATH**/ ?>