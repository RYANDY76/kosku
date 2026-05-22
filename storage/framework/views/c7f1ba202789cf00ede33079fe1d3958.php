<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> - KosKu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/dashboard.css')); ?>">
</head>
<body>
<div class="dashboard-shell">
    <?php if(auth()->user()->role === 'admin'): ?>
        <?php echo $__env->make('components.sidebar-admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php elseif(auth()->user()->role === 'pemilik'): ?>
        <?php echo $__env->make('components.sidebar-pemilik', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>
    <div class="dashboard-main <?php echo e(auth()->user()->role === 'user' ? 'full' : ''); ?>">
        <?php
            $roleLabel = match(auth()->user()->role) {
                'admin' => 'Administrator',
                'pemilik' => 'Pemilik Kos',
                default => 'Pencari Kos',
            };
            $roleIcon = match(auth()->user()->role) {
                'admin' => 'bi-shield-check',
                'pemilik' => 'bi-building',
                default => 'bi-person',
            };
        ?>
        <div class="dashboard-topbar professional-topbar">
            <div class="d-flex align-items-center gap-3">
                <a href="<?php echo e(route('home')); ?>" class="btn btn-light btn-sm topbar-home"><i class="bi bi-house"></i></a>
                <div>
                    <small class="text-muted">KosKu Management</small>
                    <h5 class="mb-0 fw-bold"><?php echo $__env->yieldContent('page_title', 'Dashboard'); ?></h5>
                </div>
            </div>
            <div class="dropdown dashboard-profile-dropdown">
                <button class="dashboard-profile-btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="dashboard-profile-avatar"><i class="bi <?php echo e($roleIcon); ?>"></i></span>
                    <span><strong><?php echo e(auth()->user()->name); ?></strong><small><?php echo e($roleLabel); ?></small></span>
                </button>
                <div class="dropdown-menu dropdown-menu-end shadow-sm dashboard-profile-menu">
                    <div class="dashboard-profile-head">
                        <span class="dashboard-profile-avatar lg"><i class="bi <?php echo e($roleIcon); ?>"></i></span>
                        <div><strong><?php echo e(auth()->user()->name); ?></strong><small><?php echo e(auth()->user()->email); ?></small><em><?php echo e($roleLabel); ?></em></div>
                    </div>
                    <a class="dropdown-item" href="<?php echo e(route('profile.show')); ?>"><i class="bi bi-person-circle me-2"></i>Profil Saya</a>
                    <a class="dropdown-item" href="<?php echo e(route('dashboard.index')); ?>"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
                    <a class="dropdown-item" href="<?php echo e(route('kos.index')); ?>"><i class="bi bi-window-stack me-2"></i>Lihat Website</a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="<?php echo e(route('logout')); ?>"><?php echo csrf_field(); ?><button class="dropdown-item text-danger"><i class="bi bi-box-arrow-right me-2"></i>Logout</button></form>
                </div>
            </div>
        </div>
        <div class="dashboard-content">
            <?php echo $__env->make('components.alert', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('[data-confirm]').forEach(btn => btn.addEventListener('click', function(e){ if(!confirm(this.dataset.confirm)){ e.preventDefault(); }}));
document.querySelectorAll('.js-auto-alert').forEach(alertEl => {
    setTimeout(() => {
        const alert = bootstrap.Alert.getOrCreateInstance(alertEl);
        alert.close();
    }, 2800);
});

const searchInput = document.querySelector('[data-table-search]');
const statusInput = document.querySelector('[data-table-status]');
function filterAdminTables(){
    const q = (searchInput?.value || '').toLowerCase().trim();
    const st = (statusInput?.value || '').toLowerCase().trim();
    document.querySelectorAll('.table-admin tbody tr, .pro-table tbody tr').forEach(row => {
        const text = row.innerText.toLowerCase();
        const okText = !q || text.includes(q);
        const okStatus = !st || text.includes(st);
        row.style.display = okText && okStatus ? '' : 'none';
    });
}
searchInput?.addEventListener('input', filterAdminTables);
statusInput?.addEventListener('change', filterAdminTables);
document.querySelectorAll('.js-payment-preview').forEach(input => input.addEventListener('change', e => {
    const img = input.closest('form')?.querySelector('.payment-preview-thumb');
    const file = e.target.files?.[0];
    if (img && file) { img.src = URL.createObjectURL(file); img.classList.remove('d-none'); }
}));
</script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\KosKu_UAS_FINAL_UIUX_PRO\resources\views/layouts/dashboard.blade.php ENDPATH**/ ?>