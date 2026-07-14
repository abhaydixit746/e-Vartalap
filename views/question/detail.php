<?php $pageTitle = htmlspecialchars($question['title'], ENT_QUOTES, 'UTF-8'); ?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container my-4">
  <div class="row">
    <div class="col-lg-9">

      <!-- Breadcrumb -->
      <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="/">Home</a></li>
          <li class="breadcrumb-item active">Question</li>
        </ol>
      </nav>

      <!-- Question Card -->
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <h3 class="fw-bold text-primary mb-0"><?= h($question['title']) ?></h3>
            <?php if ($question['status'] !== 'APPROVED'): ?>
              <span class="badge bg-warning text-dark ms-2"><?= h($question['status']) ?></span>
            <?php endif; ?>
          </div>

          <?php if ($question['body']): ?>
            <div class="mt-3 mb-3">
              <p class="text-dark" style="white-space:pre-wrap;line-height:1.75"><?= h($question['body']) ?></p>
            </div>
          <?php endif; ?>

          <!-- Tags -->
          <?php if (!empty($tags)): ?>
            <div class="mb-3">
              <?php foreach ($tags as $tag): ?>
                <span class="badge bg-secondary-subtle text-secondary me-1 px-2 py-1"><?= h($tag['name']) ?></span>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>

          <!-- Meta bar -->
          <div class="d-flex align-items-center justify-content-between text-muted small border-top pt-3">
            <div class="d-flex gap-3">
              <span><i class="bi bi-eye me-1"></i><?= (int)$question['view_count'] ?> views</span>
              <span><i class="bi bi-chat-left-text me-1"></i><?= count($answers) ?> answers</span>
            </div>
            <div class="d-flex align-items-center gap-2">
              <img src="<?= h(\App\Model\UserModel::photoUrl($question['photo_path'])) ?>"
                   class="rounded-circle" width="26" height="26" style="object-fit:cover"
                   alt="" onerror="this.src='/img/default-avatar.svg'">
              <div>
                <span class="fw-semibold text-dark"><?= h($question['first_name'] . ' ' . $question['last_name']) ?></span>
                <span class="ms-2">asked <?= fmtDate($question['created_at']) ?></span>
              </div>
            </div>
          </div>

          <!-- Admin approve/reject on question -->
          <?php if ($isAdmin && $question['status'] === 'PENDING'): ?>
            <div class="mt-3 pt-3 border-top d-flex gap-2">
              <form action="/admin/questions/<?= (int)$question['id'] ?>/approve" method="POST">
                <?= csrfField() ?>
                <button class="btn btn-success btn-sm"><i class="bi bi-check-lg me-1"></i>Approve</button>
              </form>
              <form action="/admin/questions/<?= (int)$question['id'] ?>/reject" method="POST">
                <?= csrfField() ?>
                <button class="btn btn-danger btn-sm"><i class="bi bi-x-lg me-1"></i>Reject</button>
              </form>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Answers -->
      <h5 class="fw-bold mb-3">
        <i class="bi bi-chat-left-dots me-2 text-primary"></i>
        <?= count($answers) ?> Answer<?= count($answers) !== 1 ? 's' : '' ?>
      </h5>

      <?php if (empty($answers)): ?>
        <div class="text-center py-4 text-muted bg-light rounded mb-4">
          <i class="bi bi-chat-square fs-2 d-block mb-2"></i>
          No answers yet. Be the first to answer!
        </div>
      <?php else: ?>
        <?php foreach ($answers as $ans): ?>
          <div class="card border-0 shadow-sm mb-3 <?= $ans['is_accepted'] ? 'border-start border-success border-3' : '' ?>">
            <div class="card-body p-4">
              <?php if ($ans['is_accepted']): ?>
                <div class="text-success fw-semibold small mb-2">
                  <i class="bi bi-patch-check-fill me-1"></i>Accepted Answer
                </div>
              <?php endif; ?>
              <p class="text-dark mb-3" style="white-space:pre-wrap;line-height:1.75"><?= h($ans['body']) ?></p>
              <div class="d-flex align-items-center justify-content-between text-muted small border-top pt-2">
                <div class="d-flex align-items-center gap-2">
                  <?php if ($ans['status'] !== 'APPROVED'): ?>
                    <span class="badge bg-warning text-dark"><?= h($ans['status']) ?></span>
                  <?php endif; ?>
                  <!-- Accept button for question author -->
                  <?php if ($isLoggedIn && !$ans['is_accepted'] && (int)$currentUser['id'] === (int)$question['author_id']): ?>
                    <form action="/questions/answers/<?= (int)$ans['id'] ?>/accept" method="POST">
                      <?= csrfField() ?>
                      <input type="hidden" name="question_id" value="<?= (int)$question['id'] ?>">
                      <button class="btn btn-outline-success btn-sm">
                        <i class="bi bi-check-circle me-1"></i>Accept
                      </button>
                    </form>
                  <?php endif; ?>
                  <!-- Admin approve/reject answer -->
                  <?php if ($isAdmin && $ans['status'] === 'PENDING'): ?>
                    <form action="/admin/answers/<?= (int)$ans['id'] ?>/approve" method="POST" class="d-inline">
                      <?= csrfField() ?>
                      <button class="btn btn-success btn-sm ms-1"><i class="bi bi-check-lg"></i> Approve</button>
                    </form>
                    <form action="/admin/answers/<?= (int)$ans['id'] ?>/reject" method="POST" class="d-inline">
                      <?= csrfField() ?>
                      <button class="btn btn-danger btn-sm ms-1"><i class="bi bi-x-lg"></i> Reject</button>
                    </form>
                  <?php endif; ?>
                </div>
                <div class="d-flex align-items-center gap-2">
                  <img src="<?= h(\App\Model\UserModel::photoUrl($ans['photo_path'])) ?>"
                       class="rounded-circle" width="22" height="22" style="object-fit:cover"
                       alt="" onerror="this.src='/img/default-avatar.svg'">
                  <span class="fw-semibold text-dark"><?= h($ans['first_name'] . ' ' . $ans['last_name']) ?></span>
                  <span>answered <?= fmtDate($ans['created_at']) ?></span>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>

      <!-- Submit Answer Form -->
      <div class="card border-0 shadow-sm mt-4" id="answer-form">
        <div class="card-header bg-light fw-bold py-3">
          <i class="bi bi-pencil-square me-2 text-primary"></i>Your Answer
        </div>
        <div class="card-body p-4">
          <?php if ($isLoggedIn): ?>
            <?php if (!empty($answerError)): ?>
              <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= h($answerError) ?></div>
            <?php endif; ?>
            <form action="/questions/<?= (int)$question['id'] ?>/answer" method="POST">
              <?= csrfField() ?>
              <textarea name="body" class="form-control mb-3" rows="8"
                        placeholder="Write a detailed, helpful answer…"
                        required><?= h($answerOld ?? '') ?></textarea>
              <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-send-fill me-2"></i>Post Answer
              </button>
              <p class="text-muted small mt-2 mb-0">
                <i class="bi bi-info-circle me-1"></i>
                Your answer will be reviewed by an admin before appearing publicly.
              </p>
            </form>
          <?php else: ?>
            <div class="text-center py-4">
              <p class="text-muted">You must be signed in to post an answer.</p>
              <a href="/auth/login" class="btn btn-primary me-2">
                <i class="bi bi-box-arrow-in-right me-1"></i>Sign In
              </a>
              <a href="/auth/register" class="btn btn-outline-primary">
                <i class="bi bi-person-plus me-1"></i>Register
              </a>
            </div>
          <?php endif; ?>
        </div>
      </div>

    </div><!-- /col-lg-9 -->

    <!-- Sidebar -->
    <div class="col-lg-3 d-none d-lg-block">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h6 class="fw-bold text-primary mb-3"><i class="bi bi-lightbulb me-1"></i>Tips for a Good Answer</h6>
          <ul class="small text-muted ps-3">
            <li class="mb-1">Be specific and clear</li>
            <li class="mb-1">Include examples if possible</li>
            <li class="mb-1">Reference sources when relevant</li>
            <li>Be respectful and constructive</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
