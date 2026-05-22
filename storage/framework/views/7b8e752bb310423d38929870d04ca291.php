<?php $__env->startSection('title', 'Login'); ?>
<?php $__env->startSection('content'); ?>
<div class="auth-pro-page">
    <div class="auth-pro-card auth-login-card">
        <div class="auth-pro-head">
            <a href="<?php echo e(route('home')); ?>" class="auth-pro-logo"><i class="bi bi-house-heart"></i></a>
            <span class="auth-pro-kicker">KosKu Account</span>
            <h1>Masuk ke KosKu</h1>
            <p>Kelola kos, pantau booking, atau temukan kamar terbaik dengan akun Anda.</p>
        </div>

        <form method="POST" action="<?php echo e(route('login.store')); ?>" class="auth-pro-form">
            <?php echo csrf_field(); ?>
            <div class="auth-field">
                <label>Email</label>
                <div class="auth-input-wrap">
                    <i class="bi bi-envelope"></i>
                    <input type="email" name="email" value="<?php echo e(old('email')); ?>" placeholder="nama@email.com" required autofocus>
                </div>
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="auth-error"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="auth-field">
                <label>Password</label>
                <div class="auth-input-wrap">
                    <i class="bi bi-lock"></i>
                    <input type="password" name="password" placeholder="Masukkan password" required>
                </div>
            </div>

            <div class="auth-meta-row">
                <label class="auth-check"><input type="checkbox" name="remember"> <span>Ingat saya</span></label>
                <a href="<?php echo e(route('password.request')); ?>">Lupa password?</a>
            </div>

            <button class="btn btn-primary auth-pro-btn"><i class="bi bi-box-arrow-in-right me-1"></i> Login</button>
        </form>

        <div class="auth-switch-text">
            Belum punya akun? <a href="<?php echo e(route('register')); ?>">Daftar sekarang</a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\KosKu_UAS_FINAL_UIUX_PRO\resources\views/auth/login.blade.php ENDPATH**/ ?>