<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('title', 'KosKu'); ?> - Marketplace Kos Palu</title>
    <meta name="description" content="KosKu membantu pencari kos menemukan kos di Palu berdasarkan lokasi, harga, fasilitas, dan ketersediaan kamar.">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/style.css')); ?>">
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <?php if (! (request()->routeIs('login') || request()->routeIs('register'))): ?>
        <?php echo $__env->make('components.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>
    <main>
        <?php echo $__env->make('components.alert', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->yieldContent('content'); ?>
    </main>
    <?php if (! (request()->routeIs('login') || request()->routeIs('register'))): ?>
        <?php echo $__env->make('components.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.querySelectorAll('[data-confirm]').forEach(btn => btn.addEventListener('click', function(e){ if(!confirm(this.dataset.confirm)){ e.preventDefault(); }}));
        document.querySelectorAll('.js-auto-alert').forEach(alertEl => {
            setTimeout(() => {
                const alert = bootstrap.Alert.getOrCreateInstance(alertEl);
                alert.close();
            }, 2800);
        });
        document.querySelector('.btn-share-page')?.addEventListener('click', async function(){ const data = {title: document.title, url: window.location.href}; if(navigator.share){ await navigator.share(data); } else { await navigator.clipboard.writeText(window.location.href); alert('Link halaman berhasil disalin.'); }});
        document.querySelectorAll('.listing-grid-body, .listing-grid-footer > div').forEach(el => { el.addEventListener('click', function(e){ if(e.target.closest('a, button, form, input, select')) return; const card = this.closest('.listing-grid-card'); const href = card?.querySelector('h3 a, .listing-grid-photo')?.href; if(href) window.location = href; }); });
        document.querySelectorAll('.js-money-field').forEach(input => { const money = value => 'Rp ' + new Intl.NumberFormat('id-ID').format(value || 0); const digits = value => parseInt(String(value || '').replace(/[^0-9]/g, ''), 10) || 0; input.addEventListener('focus', () => { input.value = digits(input.value) || ''; input.select(); }); input.addEventListener('blur', () => { const value = digits(input.value); input.value = value ? money(value) : ''; }); input.closest('form')?.addEventListener('submit', () => { input.value = digits(input.value) || ''; }); });
        document.querySelectorAll('.js-payment-preview').forEach(input => input.addEventListener('change', e => { const img = input.closest('form')?.querySelector('.payment-preview-thumb'); const file = e.target.files?.[0]; if(img && file){ img.src = URL.createObjectURL(file); img.classList.remove('d-none'); } }));
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\KosKu_UAS_FINAL_UIUX_PRO\resources\views/layouts/app.blade.php ENDPATH**/ ?>