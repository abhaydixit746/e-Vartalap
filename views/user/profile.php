<?php $pageTitle = 'My Profile'; ?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container my-4">
  <div class="row">

    <!-- Sidebar photo -->
    <div class="col-lg-3 mb-4">
      <div class="card border-0 shadow-sm text-center">
        <div class="card-body py-4">
          <img src="<?= h(\App\Model\UserModel::photoUrl($profile['photo_path'])) ?>"
               class="rounded-circle border border-3 border-primary mb-3"
               id="photoPreview"
               width="110" height="110" style="object-fit:cover"
               alt="<?= h($profile['first_name']) ?>"
               onerror="this.src='/img/default-avatar.svg'">
          <h5 class="fw-bold mb-0"><?= h($profile['first_name'] . ' ' . $profile['last_name']) ?></h5>
          <p class="text-muted small mb-1">@<?= h($profile['username']) ?></p>
          <?php if ($profile['designation'] && $profile['company']): ?>
            <p class="text-muted small"><?= h($profile['designation']) ?> at <?= h($profile['company']) ?></p>
          <?php endif; ?>
          <span class="badge <?= $profile['role'] === 'ADMIN' ? 'bg-danger' : 'bg-primary' ?>">
            <?= h($profile['role']) ?>
          </span>
        </div>
      </div>
    </div>

    <!-- Edit form -->
    <div class="col-lg-9">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
          <h5 class="fw-bold mb-0"><i class="bi bi-pencil-square me-2 text-primary"></i>Edit Profile</h5>
        </div>
        <div class="card-body p-4">

          <?php if (!empty($success)): ?>
            <div class="alert alert-success">
              <i class="bi bi-check-circle-fill me-2"></i><?= h($success) ?>
            </div>
          <?php endif; ?>

          <form action="/profile" method="POST" enctype="multipart/form-data" novalidate>
            <?= csrfField() ?>

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                <input type="text" name="first_name"
                       class="form-control <?= isset($errors['first_name']) ? 'is-invalid' : '' ?>"
                       value="<?= h($profile['first_name']) ?>" required>
                <?php if (isset($errors['first_name'])): ?>
                  <div class="invalid-feedback"><?= h($errors['first_name']) ?></div>
                <?php endif; ?>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                <input type="text" name="last_name"
                       class="form-control <?= isset($errors['last_name']) ? 'is-invalid' : '' ?>"
                       value="<?= h($profile['last_name']) ?>" required>
                <?php if (isset($errors['last_name'])): ?>
                  <div class="invalid-feedback"><?= h($errors['last_name']) ?></div>
                <?php endif; ?>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                <input type="email" name="email"
                       class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                       value="<?= h($profile['email']) ?>" required>
                <?php if (isset($errors['email'])): ?>
                  <div class="invalid-feedback"><?= h($errors['email']) ?></div>
                <?php endif; ?>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Contact</label>
                <input type="text" name="contact" class="form-control"
                       value="<?= h($profile['contact'] ?? '') ?>">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Company</label>
                <input type="text" name="company" class="form-control"
                       value="<?= h($profile['company'] ?? '') ?>" placeholder="Your company or organization">
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold">Designation</label>
                <input type="text" name="designation" class="form-control"
                       value="<?= h($profile['designation'] ?? '') ?>" placeholder="Your job title">
              </div>
              <div class="col-12">
                <label class="form-label fw-semibold">Profile Photo</label>
                <input type="file" name="photo" id="photoInput"
                       class="form-control <?= isset($errors['photo']) ? 'is-invalid' : '' ?>"
                       accept="image/jpeg,image/png,image/gif,image/webp">
                <?php if (isset($errors['photo'])): ?>
                  <div class="invalid-feedback"><?= h($errors['photo']) ?></div>
                <?php endif; ?>
                <div class="text-muted small mt-1">Max 5MB. JPG, PNG, GIF or WEBP. Leave blank to keep current.</div>
              </div>
            </div>

            <div class="d-flex gap-2 mt-4">
              <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-save-fill me-2"></i>Save Changes
              </button>
              <a href="/" class="btn btn-outline-secondary">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
// Preview photo before upload
document.getElementById('photoInput')?.addEventListener('change', function () {
  const file = this.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = e => document.getElementById('photoPreview').src = e.target.result;
  reader.readAsDataURL(file);
});
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>
