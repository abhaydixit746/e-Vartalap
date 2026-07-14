<?php $pageTitle = 'Admin Dashboard'; ?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container my-4">
  <h4 class="fw-bold mb-4"><i class="bi bi-shield-lock-fill me-2 text-danger"></i>Admin Dashboard</h4>

  <?php if (!empty($success)): ?>
    <div class="alert alert-success alert-dismissible fade show">
      <i class="bi bi-check-circle-fill me-2"></i><?= h($success) ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <div class="row g-4 mb-4">
    <!-- Pending questions -->
    <div class="col-md-6 col-lg-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="rounded-3 bg-warning bg-opacity-10 p-3">
            <i class="bi bi-question-circle-fill text-warning fs-2"></i>
          </div>
          <div>
            <div class="fs-1 fw-bold text-warning"><?= (int)$pendingQuestions ?></div>
            <div class="text-muted">Questions Pending</div>
          </div>
        </div>
        <div class="card-footer bg-transparent border-0 pb-3 px-3">
          <a href="/admin/questions" class="btn btn-warning btn-sm w-100">
            <i class="bi bi-eye me-1"></i>Review Questions
          </a>
        </div>
      </div>
    </div>
    <!-- Pending answers -->
    <div class="col-md-6 col-lg-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="rounded-3 bg-info bg-opacity-10 p-3">
            <i class="bi bi-chat-left-dots-fill text-info fs-2"></i>
          </div>
          <div>
            <div class="fs-1 fw-bold text-info"><?= (int)$pendingAnswers ?></div>
            <div class="text-muted">Answers Pending</div>
          </div>
        </div>
        <div class="card-footer bg-transparent border-0 pb-3 px-3">
          <a href="/admin/answers" class="btn btn-info btn-sm w-100 text-white">
            <i class="bi bi-eye me-1"></i>Review Answers
          </a>
        </div>
      </div>
    </div>
    <!-- Users -->
    <div class="col-md-6 col-lg-4">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="rounded-3 bg-success bg-opacity-10 p-3">
            <i class="bi bi-people-fill text-success fs-2"></i>
          </div>
          <div>
            <div class="text-muted mt-2">Community Members</div>
          </div>
        </div>
        <div class="card-footer bg-transparent border-0 pb-3 px-3">
          <a href="/users" class="btn btn-success btn-sm w-100">
            <i class="bi bi-people me-1"></i>View All Users
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
