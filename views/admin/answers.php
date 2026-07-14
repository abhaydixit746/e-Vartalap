<?php $pageTitle = 'Review Answers'; ?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container my-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-chat-left-dots me-2 text-primary"></i>Review Answers</h4>
    <a href="/admin" class="btn btn-outline-secondary btn-sm">
      <i class="bi bi-arrow-left me-1"></i>Dashboard
    </a>
  </div>

  <?php if (!empty($success)): ?>
    <div class="alert alert-success alert-dismissible fade show">
      <i class="bi bi-check-circle-fill me-2"></i><?= h($success) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <?php if (empty($answers['items'])): ?>
    <div class="text-center py-5 text-muted">
      <i class="bi bi-inbox fs-1 d-block mb-2"></i>
      <h5>No answers pending review</h5>
    </div>
  <?php else: ?>
    <?php foreach ($answers['items'] as $ans): ?>
      <div class="card border-0 shadow-sm mb-3">
        <div class="card-body p-4">
          <div class="mb-2 d-flex align-items-center gap-2">
            <span class="text-muted small">Answer #<?= (int)$ans['id'] ?></span>
            <span class="badge bg-warning text-dark"><?= h($ans['status']) ?></span>
            <a href="/questions/<?= (int)$ans['question_id'] ?>"
               class="text-muted small text-decoration-none">
              <i class="bi bi-link-45deg"></i>
              <?= h(truncate($ans['question_title'], 60)) ?>
            </a>
          </div>
          <p class="text-dark mb-3" style="white-space:pre-wrap;line-height:1.7">
            <?= h(truncate($ans['body'], 500)) ?>
          </p>
          <div class="d-flex align-items-center justify-content-between text-muted small border-top pt-3">
            <div>
              By <strong><?= h($ans['first_name'] . ' ' . $ans['last_name']) ?></strong>
              on <?= fmtDate($ans['created_at']) ?>
            </div>
            <div class="d-flex gap-2">
              <form action="/admin/answers/<?= (int)$ans['id'] ?>/approve" method="POST">
                <?= csrfField() ?>
                <button class="btn btn-success btn-sm">
                  <i class="bi bi-check-lg me-1"></i>Approve
                </button>
              </form>
              <form action="/admin/answers/<?= (int)$ans['id'] ?>/reject" method="POST">
                <?= csrfField() ?>
                <button class="btn btn-danger btn-sm">
                  <i class="bi bi-x-lg me-1"></i>Reject
                </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>

    <?php if ($answers['pages'] > 1): ?>
      <nav class="mt-3">
        <ul class="pagination justify-content-center">
          <li class="page-item <?= $answers['currentPage'] <= 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $answers['currentPage'] - 1 ?>">Prev</a>
          </li>
          <?php for ($p = 1; $p <= $answers['pages']; $p++): ?>
            <li class="page-item <?= $p === $answers['currentPage'] ? 'active' : '' ?>">
              <a class="page-link" href="?page=<?= $p ?>"><?= $p ?></a>
            </li>
          <?php endfor; ?>
          <li class="page-item <?= $answers['currentPage'] >= $answers['pages'] ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $answers['currentPage'] + 1 ?>">Next</a>
          </li>
        </ul>
      </nav>
    <?php endif; ?>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
