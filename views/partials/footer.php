<footer class="bg-dark text-light mt-5 py-4">
  <div class="container">
    <div class="row">
      <div class="col-md-4">
        <h6 class="fw-bold text-primary"><i class="bi bi-chat-dots-fill me-1"></i>e-Vartalap</h6>
        <p class="text-muted small">A community-driven discussion forum for collaborative learning and knowledge sharing.</p>
      </div>
      <div class="col-md-4">
        <h6 class="fw-bold">Quick Links</h6>
        <ul class="list-unstyled small">
          <li><a href="/"           class="text-muted text-decoration-none">Home</a></li>
          <li><a href="/unanswered" class="text-muted text-decoration-none">Unanswered</a></li>
          <li><a href="/users"      class="text-muted text-decoration-none">Community</a></li>
        </ul>
      </div>
      <div class="col-md-4 text-md-end">
        <p class="text-muted small mb-1">Built with PHP 8 + MySQL</p>
        <a href="https://www.facebook.com" class="text-muted me-2"><i class="bi bi-facebook fs-5"></i></a>
        <a href="https://twitter.com"      class="text-muted me-2"><i class="bi bi-twitter-x fs-5"></i></a>
        <a href="mailto:contact@evartalap.com" class="text-muted"><i class="bi bi-envelope-fill fs-5"></i></a>
      </div>
    </div>
    <hr class="border-secondary">
    <p class="text-center text-muted small mb-0">&copy; <?= date('Y') ?> e-Vartalap. All rights reserved.</p>
  </div>
</footer>

<!-- Back-to-top button -->
<button id="backToTop" class="btn btn-primary rounded-circle position-fixed d-none"
        style="bottom:2rem;right:2rem;width:44px;height:44px;z-index:1000;box-shadow:0 2px 10px rgba(0,0,0,.3)"
        title="Back to top" aria-label="Back to top">
  <i class="bi bi-arrow-up"></i>
</button>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="/js/evartalap.js"></script>
</body>
</html>
