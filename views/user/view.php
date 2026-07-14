<?php $pageTitle = htmlspecialchars($profile['first_name'] . ' ' . $profile['last_name'], ENT_QUOTES, 'UTF-8'); ?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container my-4">
  <div class="row justify-content-center">
    <div class="col-md-8 col-lg-5">
      <div class="card border-0 shadow-sm text-center">
        <div class="card-body py-5">
          <img src="<?= h(\App\Model\UserModel::photoUrl($profile['photo_path'])) ?>"
               class="rounded-circle border border-3 border-primary mb-3"
               width="120" height="120" style="object-fit:cover"
               alt="<?= h($profile['first_name']) ?>"
               onerror="this.src='/img/default-avatar.svg'">
          <h4 class="fw-bold"><?= h($profile['first_name'] . ' ' . $profile['last_name']) ?></h4>
          <p class="text-muted mb-1">@<?= h($profile['username']) ?></p>
          <?php if ($profile['designation'] && $profile['company']): ?>
            <p class="text-primary fw-semibold mb-1">
              <?= h($profile['designation']) ?> at <?= h($profile['company']) ?>
            </p>
          <?php endif; ?>
          <?php if ($profile['contact']): ?>
            <p class="text-muted small">
              <i class="bi bi-telephone me-1"></i><?= h($profile['contact']) ?>
            </p>
          <?php endif; ?>
          <span class="badge <?= $profile['role'] === 'ADMIN' ? 'bg-danger' : 'bg-primary' ?>">
            <?= h($profile['role']) ?>
          </span>
          <div class="mt-3">
            <a href="/questions?keyword=<?= urlencode($profile['username']) ?>"
               class="btn btn-outline-primary btn-sm">
              <i class="bi bi-journal-text me-1"></i>View Questions
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
