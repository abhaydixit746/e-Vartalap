# e-Vartalap PHP — Online Discussion Forum

Modern PHP 8.x rewrite of the e-Vartalap discussion forum.  
**No frameworks. No Composer. Pure PHP 8 + MySQL 8 + Bootstrap 5.**

---

## Tech Stack

| Layer       | Technology                          |
|-------------|-------------------------------------|
| Language    | PHP 8.x                             |
| Database    | MySQL 8.x (PDO, prepared statements)|
| Frontend    | HTML5, CSS3, Bootstrap 5, JS ES6    |
| Web Server  | Apache (mod_rewrite) or Nginx       |
| Architecture| MVC — Router → Controller → Model → View |

---

## Requirements

- PHP 8.0+ with extensions: `pdo`, `pdo_mysql`, `mbstring`, `fileinfo`
- MySQL 8.x
- Apache with `mod_rewrite` enabled (or Nginx equivalent)

---

## Setup (5 steps)

**1. Place project files**
```
/var/www/html/evartalap/   ← or your web root
```
Point your virtual host `DocumentRoot` to the `public/` folder.

**2. Create the database**
```bash
mysql -u root -p < database/schema.sql
```
This creates the `evartalap` database, all tables, and seeds sample data.

**3. Configure the app**
Edit `config/config.php`:
```php
'db' => [
    'host'     => 'localhost',
    'dbname'   => 'evartalap',
    'username' => 'root',
    'password' => 'your_password',   // ← change this
],
```

**4. Set permissions**
```bash
chmod -R 755 public/uploads/
```

**5. Apache virtual host** (example)
```apache
<VirtualHost *:80>
    ServerName evartalap.local
    DocumentRoot /var/www/html/evartalap/public

    <Directory /var/www/html/evartalap/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Enable `mod_rewrite`:
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

---

## Default Login Credentials

| Username | Password   | Role  |
|----------|------------|-------|
| admin    | Welcome@001  | Admin |
| abhay    | Welcome@001  | User  |
| vijay      | Welcome@001  | User  |
| sanjay  | Welcome@001  | User  |
| mukesh    | Welcome@001  | User  |
| jay     | Welcome@001  | User  |

---

## Project Structure

```
evartalap-php/
├── config/
│   ├── bootstrap.php       ← autoloader + session + error handling
│   └── config.php          ← DB credentials + app settings
├── database/
│   └── schema.sql          ← full schema + seed data
├── public/                 ← DocumentRoot (web-accessible)
│   ├── .htaccess           ← front-controller rewrite rules
│   ├── index.php           ← front controller + route definitions
│   ├── css/evartalap.css
│   ├── js/evartalap.js
│   ├── img/
│   └── uploads/photos/     ← user-uploaded profile photos
├── src/
│   ├── Core/
│   │   ├── Database.php    ← PDO singleton + paginate helper
│   │   ├── Router.php      ← URL routing
│   │   ├── Request.php     ← input, CSRF, redirect, flash, JSON
│   │   └── View.php        ← template renderer + global helpers
│   ├── Controller/
│   │   ├── AuthController.php
│   │   ├── HomeController.php
│   │   ├── QuestionController.php
│   │   ├── UserController.php
│   │   └── AdminController.php
│   ├── Model/
│   │   ├── UserModel.php
│   │   ├── QuestionModel.php
│   │   └── AnswerModel.php
│   ├── Middleware/
│   │   └── AuthMiddleware.php  ← requireLogin / requireAdmin / requireGuest
│   └── Util/
│       ├── Validator.php
│       └── FileUpload.php
└── views/
    ├── partials/
    │   ├── header.php
    │   └── footer.php
    ├── index.php
    ├── auth/login.php, register.php
    ├── question/list.php, detail.php, ask.php
    ├── user/list.php, view.php, profile.php
    ├── admin/dashboard.php, questions.php, answers.php
    └── error/404.php, 403.php, 500.php
```

---

## Features

- ✅ User registration & login (BCrypt passwords)
- ✅ Session-based authentication with CSRF protection
- ✅ Ask questions with tags (pending admin approval)
- ✅ Post answers (pending admin approval)
- ✅ Accept best answer (question author only)
- ✅ Admin approve/reject questions and answers
- ✅ Search questions by keyword
- ✅ Unanswered questions filter
- ✅ My Questions page
- ✅ User profile with photo upload
- ✅ Community members directory
- ✅ Responsive Bootstrap 5 UI
- ✅ SQL injection prevention (PDO prepared statements)
- ✅ XSS prevention (htmlspecialchars on all output)
- ✅ File upload security (MIME check, size limit, no PHP in uploads)

---

## Security Notes

- All DB queries use PDO prepared statements — no SQL injection possible
- All view output uses `h()` (htmlspecialchars) — no XSS possible
- CSRF tokens on every POST form — no CSRF possible
- Session ID regenerated on login — no session fixation
- Uploaded photos verified by MIME type, not extension
- PHP execution blocked inside `public/uploads/` via `.htaccess`
- Sensitive config kept outside `public/`
