<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($pageTitle ?? 'e-Vartalap') ?> — Online Discussion Forum</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/css/evartalap.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold fs-5" href="/">
      <i class="bi bi-chat-dots-fill me-2"></i>e-Vartalap
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMain">
      <!-- Search -->
      <form class="d-flex mx-auto my-2 my-lg-0" action="/" method="get" style="width:38%">
        <div class="input-group">
          <input class="form-control" type="search" name="keyword"
                 placeholder="Search questions…"
                 value="<?= h($_GET['keyword'] ?? '') ?>" aria-label="Search">
          <button class="btn btn-light" type="submit"><i class="bi bi-search"></i></button>
        </div>
      </form>
      <!-- Nav links -->
      <ul class="navbar-nav ms-auto align-items-center gap-1">
        <li class="nav-item">
          <a class="nav-link" href="/"><i class="bi bi-house-door-fill"></i>
            <span class="d-lg-none ms-1">Home</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/unanswered"><i class="bi bi-question-circle-fill"></i>
            <span class="d-lg-none ms-1">Unanswered</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/users"><i class="bi bi-people-fill"></i>
            <span class="d-lg-none ms-1">Users</span></a>
        </li>
        <?php if (isLoggedIn()): ?>
          <li class="nav-item">
            <a class="nav-link btn btn-warning btn-sm text-dark fw-semibold px-3 ms-2" href="/questions/ask">
              <i class="bi bi-plus-circle-fill me-1"></i>Ask
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/questions/my"><i class="bi bi-journal-text"></i>
              <span class="d-lg-none ms-1">My Questions</span></a>
          </li>
          <?php if (isAdmin()): ?>
          <li class="nav-item">
            <a class="nav-link text-warning" href="/admin"><i class="bi bi-shield-lock-fill"></i>
              <span class="d-lg-none ms-1">Admin</span></a>
          </li>
          <?php endif; ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center gap-1" href="#" data-bs-toggle="dropdown">
              <?php $u = auth(); ?>
              <?php if ($u['photo_path'] ?? null): ?>
                <img src="<?= h($u['photo_path']) ?>" class="rounded-circle" width="26" height="26"
                     style="object-fit:cover" alt="avatar">
              <?php else: ?>
                <img src="/img/default-avatar.svg" class="rounded-circle" width="26" height="26" alt="avatar">
              <?php endif; ?>
              <span><?= h($u['first_name'] ?? '') ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="/profile"><i class="bi bi-person me-2"></i>Profile</a></li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <form action="/auth/logout" method="post">
                  <?= csrfField() ?>
                  <button type="submit" class="dropdown-item text-danger">
                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                  </button>
                </form>
              </li>
            </ul>
          </li>
        <?php else: ?>
          <li class="nav-item">
            <a class="nav-link" href="/auth/login">
              <i class="bi bi-box-arrow-in-right me-1"></i>Sign In
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link btn btn-outline-light btn-sm px-3 ms-1" href="/auth/register">
              <i class="bi bi-person-plus me-1"></i>Register
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- Flash Messages -->
<div class="container mt-3">
<?php $succ = flashGet('success'); $err = flashGet('error'); ?>
<?php if ($succ): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i><?= h($succ) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>
<?php if ($err): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2"></i><?= h($err) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>
<?php if (isset($_GET['logout'])): ?>
  <div class="alert alert-info alert-dismissible fade show" role="alert">
    <i class="bi bi-info-circle me-2"></i>You have been logged out.
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
<?php endif; ?>
</div>
