<?php $pageTitle = 'Create Account'; ?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container d-flex justify-content-center align-items-center py-5">
  <div class="col-12 col-sm-10 col-md-7 col-lg-5">
    <div class="card shadow-sm border-0">
      <div class="card-header bg-primary text-white text-center py-3">
        <h4 class="mb-0 fw-bold"><i class="bi bi-person-plus-fill me-2"></i>Create Account</h4>
      </div>
      <div class="card-body p-4">

        <form action="/auth/register" method="POST" novalidate>
          <?= csrfField() ?>

          <div class="row g-3">
            <!-- First Name -->
            <div class="col-6">
              <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
              <input type="text" name="first_name"
                     class="form-control <?= isset($errors['first_name']) ? 'is-invalid' : '' ?>"
                     value="<?= h($old['first_name'] ?? '') ?>" required>
              <?php if (isset($errors['first_name'])): ?>
                <div class="invalid-feedback"><?= h($errors['first_name']) ?></div>
              <?php endif; ?>
            </div>
            <!-- Last Name -->
            <div class="col-6">
              <label class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
              <input type="text" name="last_name"
                     class="form-control <?= isset($errors['last_name']) ? 'is-invalid' : '' ?>"
                     value="<?= h($old['last_name'] ?? '') ?>" required>
              <?php if (isset($errors['last_name'])): ?>
                <div class="invalid-feedback"><?= h($errors['last_name']) ?></div>
              <?php endif; ?>
            </div>
            <!-- Email -->
            <div class="col-12">
              <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email"
                       class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                       value="<?= h($old['email'] ?? '') ?>" placeholder="you@example.com" required>
                <?php if (isset($errors['email'])): ?>
                  <div class="invalid-feedback"><?= h($errors['email']) ?></div>
                <?php endif; ?>
              </div>
            </div>
            <!-- Username -->
            <div class="col-12">
              <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-at"></i></span>
                <input type="text" id="usernameInput" name="username"
                       class="form-control <?= isset($errors['username']) ? 'is-invalid' : '' ?>"
                       value="<?= h($old['username'] ?? '') ?>" placeholder="Choose a username" required>
                <?php if (isset($errors['username'])): ?>
                  <div class="invalid-feedback"><?= h($errors['username']) ?></div>
                <?php endif; ?>
              </div>
              <div id="usernameStatus" class="small mt-1"></div>
            </div>
            <!-- Contact -->
            <div class="col-12">
              <label class="form-label fw-semibold">Contact <span class="text-muted fw-normal small">(optional)</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                <input type="text" name="contact" class="form-control"
                       value="<?= h($old['contact'] ?? '') ?>" placeholder="+91 9999999999">
              </div>
            </div>
            <!-- Password -->
            <div class="col-12">
              <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" id="passwordInput" name="password"
                       class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                       placeholder="Min. 6 characters" required>
                <button class="btn btn-outline-secondary" type="button" id="togglePwd">
                  <i class="bi bi-eye"></i>
                </button>
                <?php if (isset($errors['password'])): ?>
                  <div class="invalid-feedback"><?= h($errors['password']) ?></div>
                <?php endif; ?>
              </div>
              <div class="progress mt-2" style="height:4px">
                <div id="strengthBar" class="progress-bar" style="width:0%;transition:width .3s"></div>
              </div>
            </div>
          </div>

          <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold mt-4">
            <i class="bi bi-person-check-fill me-2"></i>Create Account
          </button>
        </form>

        <hr class="my-3">
        <p class="text-center text-muted mb-0 small">
          Already have an account? <a href="/auth/login" class="fw-semibold">Sign In</a>
        </p>
      </div>
    </div>
  </div>
</div>

<script>
// Toggle password
document.getElementById('togglePwd').addEventListener('click', function () {
  const p = document.getElementById('passwordInput');
  const i = this.querySelector('i');
  p.type = p.type === 'password' ? 'text' : 'password';
  i.className = p.type === 'password' ? 'bi bi-eye' : 'bi bi-eye-slash';
});

// Password strength
document.getElementById('passwordInput').addEventListener('input', function () {
  const v = this.value, bar = document.getElementById('strengthBar');
  let s = 0;
  if (v.length >= 6)            s++;
  if (/[A-Z]/.test(v))          s++;
  if (/[0-9]/.test(v))          s++;
  if (/[^A-Za-z0-9]/.test(v))  s++;
  const pct = (s / 4) * 100;
  const cls = ['bg-danger','bg-danger','bg-warning','bg-info','bg-success'];
  bar.style.width = pct + '%';
  bar.className = 'progress-bar ' + (cls[s] || 'bg-danger');
});

// Username availability (debounced fetch)
let uTimer;
document.getElementById('usernameInput').addEventListener('input', function () {
  clearTimeout(uTimer);
  const val = this.value.trim();
  const status = document.getElementById('usernameStatus');
  if (val.length < 3) { status.textContent = ''; return; }
  uTimer = setTimeout(async () => {
    try {
      const r = await fetch('/auth/check-username?username=' + encodeURIComponent(val));
      const d = await r.json();
      status.className = 'small mt-1 ' + (d.available ? 'text-success' : 'text-danger');
      status.textContent = d.available ? '✓ Username available' : '✗ Username already taken';
    } catch (_) {}
  }, 400);
});
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>
