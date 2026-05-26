<?php require __DIR__ . '/../layout_header.php'; ?>

<h1>Add New Product</h1>

<?php if (!empty($error)): ?>
    <div class="alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div style="background:#fff;padding:28px;border-radius:6px;box-shadow:0 1px 4px rgba(0,0,0,.1);max-width:480px;">
<form method="POST" action="/products/create">
    <div style="margin-bottom:16px;">
        <label style="display:block;margin-bottom:6px;font-weight:bold;">Product Name *</label>
        <input type="text" name="name" placeholder="e.g. Tablet"
               value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
               style="width:100%;padding:9px 12px;border:1px solid #ccc;border-radius:4px;font-size:14px;">
    </div>
    <div style="margin-bottom:16px;">
        <label style="display:block;margin-bottom:6px;font-weight:bold;">Price ($)</label>
        <input type="number" name="price" step="0.01" min="0" placeholder="0.00"
               value="<?= htmlspecialchars($_POST['price'] ?? '') ?>"
               style="width:100%;padding:9px 12px;border:1px solid #ccc;border-radius:4px;font-size:14px;">
    </div>
    <div style="margin-bottom:20px;">
        <label style="display:block;margin-bottom:6px;font-weight:bold;">Stock</label>
        <input type="number" name="stock" min="0" placeholder="0"
               value="<?= htmlspecialchars($_POST['stock'] ?? '') ?>"
               style="width:100%;padding:9px 12px;border:1px solid #ccc;border-radius:4px;font-size:14px;">
    </div>
    <button type="submit" class="btn btn-primary">Save Product</button>
    <a href="/products" style="margin-left:12px;color:#555;text-decoration:none;">Cancel</a>
</form>
</div>

<?php require __DIR__ . '/../layout_footer.php'; ?>
