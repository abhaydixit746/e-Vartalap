<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container my-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">
      <i class="bi bi-list-ul me-2 text-primary"></i><?= h($pageTitle ?? 'Questions') ?>
    </h4>
    <?php if (isLoggedIn()): ?>
      <a href="/questions/ask" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i>Ask Question
      </a>
    <?php endif; ?>
  </div>

  <?php if (empty($questions['items'])): ?>
    <div class="text-center py-5 text-muted">
      <i class="bi bi-inbox fs-1 d-block mb-3"></i>
      <h5>No questions here yet</h5>
    </div>
  <?php else: ?>
    <?php foreach ($questions['items'] as $q): ?>
      <div class="card border-0 shadow-sm mb-3 question-card">
        <div class="card-body">
          <div class="d-flex gap-3">
            <div class="stats-col text-center d-none d-md-flex flex-column justify-content-center align-items-center">
              <div class="stat-box <?= (int)$q['answer_count'] > 0 ? 'bg-success text-white' : 'bg-light text-muted' ?> rounded p-2 mb-1">
                <div class="fw-bold fs-5"><?= (int)$q['answer_count'] ?></div>
                <div class="small">answers</div>
              </div>
              <div class="text-muted small mt-1"><i class="bi bi-eye"></i> <?= (int)$q['view_count'] ?></div>
            </div>
            <div class="flex-grow-1">
              <h5 class="mb-1">
                <a href="/questions/<?= (int)$q['id'] ?>"
                   class="text-decoration-none text-primary fw-semibold question-title">
                  <?= h($q['title']) ?>
                </a>
              </h5>
              <?php if ($q['body']): ?>
                <p class="text-muted small mb-2 question-excerpt"><?= h(truncate($q['body'], 160)) ?></p>
              <?php endif; ?>
              <?php
                $tags = \App\Core\Database::query(
                    'SELECT t.name FROM tags t JOIN question_tags qt ON qt.tag_id=t.id WHERE qt.question_id=?',
                    [(int)$q['id']]
                );
              ?>
              <?php if ($tags): ?>
                <div class="mb-2">
                  <?php foreach ($tags as $tag): ?>
                    <span class="badge bg-secondary-subtle text-secondary me-1"><?= h($tag['name']) ?></span>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
              <?php if ($q['status'] !== 'APPROVED'): ?>
                <span class="badge bg-warning text-dark me-2"><?= h($q['status']) ?></span>
              <?php endif; ?>
              <div class="d-flex align-items-center mt-2 text-muted small">
                <img src="<?= h(\App\Model\UserModel::photoUrl($q['photo_path'])) ?>"
                     class="rounded-circle me-1" width="20" height="20" style="object-fit:cover"
                     alt="" onerror="this.src='/img/default-avatar.svg'">
                <span><?= h($q['first_name'] . ' ' . $q['last_name']) ?></span>
                <span class="mx-2">·</span>
                <span><?= fmtDate($q['created_at'], 'd M Y') ?></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>

    <?php if ($questions['pages'] > 1): ?>
      <nav class="mt-4">
        <ul class="pagination justify-content-center">
          <li class="page-item <?= $questions['currentPage'] <= 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $questions['currentPage'] - 1 ?>">
              <i class="bi bi-chevron-left"></i> Prev
            </a>
          </li>
          <?php for ($p = 1; $p <= $questions['pages']; $p++): ?>
            <li class="page-item <?= $p === $questions['currentPage'] ? 'active' : '' ?>">
              <a class="page-link" href="?page=<?= $p ?>"><?= $p ?></a>
            </li>
          <?php endfor; ?>
          <li class="page-item <?= $questions['currentPage'] >= $questions['pages'] ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $questions['currentPage'] + 1 ?>">
              Next <i class="bi bi-chevron-right"></i>
            </a>
          </li>
        </ul>
      </nav>
    <?php endif; ?>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
