<?php $pageTitle = 'Review Questions'; ?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container my-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-question-circle me-2 text-primary"></i>Review Questions</h4>
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

  <?php if (empty($questions['items'])): ?>
    <div class="text-center py-5 text-muted">
      <i class="bi bi-inbox fs-1 d-block mb-2"></i>
      <h5>No questions to review</h5>
    </div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-hover align-middle bg-white rounded shadow-sm">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Question</th>
            <th>Author</th>
            <th>Status</th>
            <th>Date</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($questions['items'] as $q): ?>
            <tr>
              <td class="text-muted small"><?= (int)$q['id'] ?></td>
              <td style="max-width:280px">
                <a href="/questions/<?= (int)$q['id'] ?>" class="text-decoration-none fw-semibold text-primary">
                  <?= h(truncate($q['title'], 80)) ?>
                </a>
              </td>
              <td class="small"><?= h($q['author_username']) ?></td>
              <td>
                <span class="badge <?= match($q['status']) {
                  'APPROVED' => 'bg-success',
                  'REJECTED' => 'bg-danger',
                  default    => 'bg-warning text-dark'
                } ?>"><?= h($q['status']) ?></span>
              </td>
              <td class="small text-muted"><?= fmtDate($q['created_at'], 'd M Y') ?></td>
              <td>
                <?php if ($q['status'] === 'PENDING'): ?>
                  <form action="/admin/questions/<?= (int)$q['id'] ?>/approve" method="POST" class="d-inline">
                    <?= csrfField() ?>
                    <button class="btn btn-success btn-sm" title="Approve">
                      <i class="bi bi-check-lg"></i>
                    </button>
                  </form>
                  <form action="/admin/questions/<?= (int)$q['id'] ?>/reject" method="POST" class="d-inline ms-1">
                    <?= csrfField() ?>
                    <button class="btn btn-danger btn-sm" title="Reject">
                      <i class="bi bi-x-lg"></i>
                    </button>
                  </form>
                <?php endif; ?>
                <a href="/questions/<?= (int)$q['id'] ?>" class="btn btn-outline-primary btn-sm ms-1" title="View">
                  <i class="bi bi-eye"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <?php if ($questions['pages'] > 1): ?>
      <nav class="mt-3">
        <ul class="pagination justify-content-center">
          <li class="page-item <?= $questions['currentPage'] <= 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $questions['currentPage'] - 1 ?>">Prev</a>
          </li>
          <?php for ($p = 1; $p <= $questions['pages']; $p++): ?>
            <li class="page-item <?= $p === $questions['currentPage'] ? 'active' : '' ?>">
              <a class="page-link" href="?page=<?= $p ?>"><?= $p ?></a>
            </li>
          <?php endfor; ?>
          <li class="page-item <?= $questions['currentPage'] >= $questions['pages'] ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $questions['currentPage'] + 1 ?>">Next</a>
          </li>
        </ul>
      </nav>
    <?php endif; ?>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
