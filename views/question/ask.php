<?php $pageTitle = 'Ask a Question'; ?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container my-4">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-primary text-white py-3">
          <h4 class="mb-0 fw-bold"><i class="bi bi-patch-question-fill me-2"></i>Ask a Question</h4>
        </div>
        <div class="card-body p-4">
          <div class="alert alert-info mb-4">
            <i class="bi bi-info-circle-fill me-2"></i>
            Your question will be reviewed by an admin before it is published. Please be specific.
          </div>

          <form action="/questions/ask" method="POST" novalidate>
            <?= csrfField() ?>

            <div class="mb-4">
              <label class="form-label fw-semibold fs-6">
                Question Title <span class="text-danger">*</span>
              </label>
              <input type="text" name="title" id="titleInput"
                     class="form-control form-control-lg <?= isset($errors['title']) ? 'is-invalid' : '' ?>"
                     placeholder="What is your question? Be specific."
                     value="<?= h($old['title'] ?? '') ?>" required maxlength="500">
              <?php if (isset($errors['title'])): ?>
                <div class="invalid-feedback"><?= h($errors['title']) ?></div>
              <?php endif; ?>
              <div class="text-muted small mt-1">
                <span id="titleCount"><?= mb_strlen($old['title'] ?? '') ?></span>/500 characters
              </div>
            </div>

            <div class="mb-4">
              <label class="form-label fw-semibold">
                Description <span class="text-muted fw-normal">(optional)</span>
              </label>
              <textarea name="body" class="form-control" rows="8"
                        placeholder="Provide more details, context, what you've already tried…"><?= h($old['body'] ?? '') ?></textarea>
            </div>

            <div class="mb-4">
              <label class="form-label fw-semibold">
                Tags <span class="text-muted fw-normal small">(optional, comma-separated, max 5)</span>
              </label>
              <input type="text" name="tags" class="form-control"
                     placeholder="e.g. java, spring-boot, database"
                     value="<?= h($old['tags'] ?? '') ?>">
            </div>

            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-send-fill me-2"></i>Submit Question
              </button>
              <a href="/" class="btn btn-outline-secondary">Cancel</a>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-lg-4 d-none d-lg-block">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h6 class="fw-bold text-primary mb-3"><i class="bi bi-lightbulb-fill me-1"></i>Writing a Good Question</h6>
          <ul class="small text-muted ps-3">
            <li class="mb-2">Summarize your problem in a one-line title.</li>
            <li class="mb-2">Describe what you expected vs. what happened.</li>
            <li class="mb-2">Include code, error messages, or examples.</li>
            <li class="mb-2">Search first — your question may already be answered.</li>
            <li>Tag appropriately so experts find your question.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
const titleInput = document.getElementById('titleInput');
const titleCount = document.getElementById('titleCount');
if (titleInput) {
  titleInput.addEventListener('input', () => {
    titleCount.textContent = titleInput.value.length;
    titleCount.className = titleInput.value.length > 450 ? 'text-danger' : '';
  });
}
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>
