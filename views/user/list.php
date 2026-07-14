<?php $pageTitle = 'Community Members'; ?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container my-4">
  <h4 class="fw-bold mb-4"><i class="bi bi-people-fill me-2 text-primary"></i>Community Members</h4>

  <?php if (empty($users['items'])): ?>
    <div class="text-center py-5 text-muted">
      <i class="bi bi-people fs-1 d-block mb-3"></i>
      <h5>No members found</h5>
    </div>
  <?php else: ?>
    <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-3">
      <?php foreach ($users['items'] as $u): ?>
        <div class="col">
          <div class="card border-0 shadow-sm h-100 text-center user-card">
            <div class="card-body py-4 px-2">
              <a href="/users/<?= (int)$u['id'] ?>">
                <img src="<?= h(\App\Model\UserModel::photoUrl($u['photo_path'])) ?>"
                     class="rounded-circle border border-2 border-light mb-3"
                     width="72" height="72" style="object-fit:cover"
                     alt="<?= h($u['first_name']) ?>"
                     onerror="this.src='/img/default-avatar.svg'">
              </a>
              <h6 class="fw-bold mb-0 text-truncate px-1">
                <a href="/users/<?= (int)$u['id'] ?>" class="text-decoration-none text-dark">
                  <?= h($u['first_name'] . ' ' . $u['last_name']) ?>
                </a>
              </h6>
              <p class="text-muted small mb-0 text-truncate px-1">
                <?php if ($u['designation'] && $u['company']): ?>
                  <?= h($u['designation']) ?> at <?= h($u['company']) ?>
                <?php elseif ($u['company']): ?>
                  <?= h($u['company']) ?>
                <?php else: ?>
                  @<?= h($u['username']) ?>
                <?php endif; ?>
              </p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <?php if ($users['pages'] > 1): ?>
      <nav class="mt-4">
        <ul class="pagination justify-content-center">
          <li class="page-item <?= $users['currentPage'] <= 1 ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $users['currentPage'] - 1 ?>">
              <i class="bi bi-chevron-left"></i>
            </a>
          </li>
          <?php for ($p = 1; $p <= $users['pages']; $p++): ?>
            <li class="page-item <?= $p === $users['currentPage'] ? 'active' : '' ?>">
              <a class="page-link" href="?page=<?= $p ?>"><?= $p ?></a>
            </li>
          <?php endfor; ?>
          <li class="page-item <?= $users['currentPage'] >= $users['pages'] ? 'disabled' : '' ?>">
            <a class="page-link" href="?page=<?= $users['currentPage'] + 1 ?>">
              <i class="bi bi-chevron-right"></i>
            </a>
          </li>
        </ul>
      </nav>
    <?php endif; ?>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
