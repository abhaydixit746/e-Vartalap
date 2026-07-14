/**
 * e-Vartalap PHP — Main JavaScript (ES6+)
 * No jQuery. Vanilla JS + Bootstrap 5 + Fetch API.
 */

'use strict';

// ---- CSRF token for all fetch requests ----
const csrfMeta  = document.querySelector('meta[name="csrf"]');
const CSRF_TOKEN = csrfMeta ? csrfMeta.content : '';

// ---- Auto-dismiss success/info alerts after 5s ----
document.querySelectorAll('.alert-success, .alert-info').forEach(el => {
  setTimeout(() => bootstrap.Alert.getOrCreateInstance(el)?.close(), 5000);
});

// ---- Back-to-top button ----
const btt = document.getElementById('backToTop');
if (btt) {
  window.addEventListener('scroll', () => {
    btt.classList.toggle('d-none', window.scrollY < 300);
  });
  btt.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
}

// ---- Navbar active link highlight ----
const path = window.location.pathname;
document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
  const href = link.getAttribute('href');
  if (href && href !== '/' && path.startsWith(href)) {
    link.classList.add('active');
  } else if (href === '/' && path === '/') {
    link.classList.add('active');
  }
});

// ---- Bootstrap tooltips ----
document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
  new bootstrap.Tooltip(el);
});

// ---- Confirm for destructive actions ----
document.querySelectorAll('[data-confirm]').forEach(el => {
  el.addEventListener('click', e => {
    if (!confirm(el.dataset.confirm || 'Are you sure?')) {
      e.preventDefault();
      e.stopPropagation();
    }
  });
});

// ---- Smooth scroll to answer form ----
document.querySelectorAll('a[href="#answer-form"]').forEach(link => {
  link.addEventListener('click', e => {
    e.preventDefault();
    document.getElementById('answer-form')?.scrollIntoView({ behavior: 'smooth' });
    setTimeout(() => document.querySelector('#answer-form textarea')?.focus(), 400);
  });
});

// ---- Character counter for inputs with data-maxlength ----
document.querySelectorAll('[data-maxlength]').forEach(input => {
  const max     = parseInt(input.dataset.maxlength, 10);
  const counter = document.getElementById(input.id + 'Count');
  if (!counter) return;
  const update = () => {
    counter.textContent = input.value.length;
    counter.className = input.value.length > max - 50 ? 'text-danger' : 'text-muted';
  };
  input.addEventListener('input', update);
  update();
});

// ---- Tag input: visual chips (progressive enhancement) ----
const tagInput = document.querySelector('input[name="tags"]');
if (tagInput) {
  tagInput.addEventListener('blur', () => {
    const raw = tagInput.value;
    // Normalize: lowercase, trim spaces, deduplicate
    const tags = [...new Set(
      raw.split(',').map(t => t.trim().toLowerCase()).filter(Boolean)
    )].slice(0, 5);
    tagInput.value = tags.join(', ');
  });
}

// ---- Fetch helper (used by username/email availability checks) ----
async function fetchJSON(url) {
  const res = await fetch(url);
  if (!res.ok) throw new Error('Network error');
  return res.json();
}

// ---- Username availability (if not already inlined in register.php) ----
// (register.php has its own inline script; this is a fallback)
