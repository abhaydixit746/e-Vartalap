<?php $pageTitle = 'Page Not Found'; ?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="container text-center py-5">
  <div class="display-1 fw-bold text-muted">404</div>
  <h3 class="fw-bold mt-2"><?= h($title ?? 'Page Not Found') ?></h3>
  <p class="text-muted"><?= h($message ?? "The page you're looking for doesn't exist.") ?></p>
  <a href="/" class="btn btn-primary mt-3"><i class="bi bi-house me-2"></i>Go Home</a>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
