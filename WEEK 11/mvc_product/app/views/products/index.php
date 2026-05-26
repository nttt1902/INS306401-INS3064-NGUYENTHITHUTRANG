<?php require __DIR__ . '/../layout_header.php'; ?>

<h1>Product List</h1>
<p style="margin-bottom:16px;">
    <a href="/products/create" class="btn btn-primary">+ Add New Product</a>
</p>

<?php if (empty($products)): ?>
    <p>No products found.</p>
<?php else: ?>
<table style="width:100%;border-collapse:collapse;background:#fff;border-radius:6px;overflow:hidden;box-shadow:0 1px 4px rgba(0,0,0,.1);">
    <thead style="background:#1a237e;color:#fff;">
        <tr>
            <th style="padding:12px 16px;text-align:left;">#</th>
            <th style="padding:12px 16px;text-align:left;">Name</th>
            <th style="padding:12px 16px;text-align:right;">Price ($)</th>
            <th style="padding:12px 16px;text-align:right;">Stock</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($products as $i => $p): ?>
        <tr style="border-bottom:1px solid #e0e0e0;<?= $i % 2 === 0 ? '' : 'background:#f9f9f9;' ?>">
            <td style="padding:10px 16px;"><?= $p['id'] ?></td>
            <td style="padding:10px 16px;"><?= htmlspecialchars($p['name']) ?></td>
            <td style="padding:10px 16px;text-align:right;"><?= number_format($p['price'], 2) ?></td>
            <td style="padding:10px 16px;text-align:right;"><?= $p['stock'] ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

<?php require __DIR__ . '/../layout_footer.php'; ?>
