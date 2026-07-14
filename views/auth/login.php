<?php $pageTitle = 'Sign In'; ?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container d-flex justify-content-center align-items-center" style="min-height:72vh">
  <div class="col-12 col-sm-10 col-md-6 col-lg-4">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-primary text-white text-center py-3">
        <h4 class="mb-0 fw-bold"><i class="bi bi-box-arrow-in-right me-2"></i>Sign In</h4>
      </div>
      <div class="card-body p-4">
        <?php if (!empty($error)): ?>
          <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= h($error) ?></div>
        <?php endif; ?>

        <form action="/auth/login" method="POST" novalidate>
          <?= csrfField() ?>
          <div class="mb-3">
            <label class="form-label fw-semibold">Username</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-person"></i></span>
              <input type="text" name="username" class="form-control"
                     placeholder="Your username"
                     value="<?= h($old['username'] ?? '') ?>"
                     required autofocus autocomplete="username">
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label fw-semibold">Password</label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-lock"></i></span>
              <input type="password" id="password" name="password" class="form-control"
                     placeholder="Your password" required autocomplete="current-password">
              <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                <i class="bi bi-eye"></i>
              </button>
            </div>
          </div>
          <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
            <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
          </button>
        </form>
        <hr class="my-3">
        <p class="text-center text-muted mb-0 small">
          Don't have an account? <a href="/auth/register" class="fw-semibold">Register here</a>
        </p>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('togglePassword').addEventListener('click', function () {
  const pwd  = document.getElementById('password');
  const icon = this.querySelector('i');
  if (pwd.type === 'password') { pwd.type = 'text';     icon.className = 'bi bi-eye-slash'; }
  else                         { pwd.type = 'password'; icon.className = 'bi bi-eye'; }
});
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>
