<?php
namespace App\Controller;

use App\Core\Request;
use App\Middleware\AuthMiddleware;
use App\Model\UserModel;
use App\Util\Validator;

class AuthController extends BaseController
{
    // ---- GET /auth/login ----
    public function loginForm(array $params = []): void
    {
        AuthMiddleware::requireGuest();
        $this->render('auth/login', [
            'error'    => Request::getFlash('error'),
            'old'      => $_SESSION['old'] ?? [],
        ]);
        unset($_SESSION['old']);
    }

    // ---- POST /auth/login ----
    public function login(array $params = []): void
    {
        AuthMiddleware::requireGuest();
        Request::verifyCsrf();

        $username = Request::post('username', '');
        $password = Request::postRaw('password', '');

        $user = UserModel::findByUsername($username);

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['old'] = ['username' => $username];
            $this->flash('error', 'Invalid username or password. Please try again.');
            $this->redirect('/auth/login');
        }

        // Session fixation prevention
        session_regenerate_id(true);

        // Store minimal user data in session
        $_SESSION['user'] = [
            'id'         => $user['id'],
            'username'   => $user['username'],
            'first_name' => $user['first_name'],
            'last_name'  => $user['last_name'],
            'email'      => $user['email'],
            'role'       => $user['role'],
            'photo_path' => $user['photo_path'],
        ];

        $this->flash('success', 'Welcome back, ' . $user['first_name'] . '!');
        $this->redirect('/');
    }

    // ---- GET /auth/register ----
    public function registerForm(array $params = []): void
    {
        AuthMiddleware::requireGuest();
        $this->render('auth/register', [
            'errors' => [],
            'old'    => $_SESSION['old'] ?? [],
        ]);
        unset($_SESSION['old']);
    }

    // ---- POST /auth/register ----
    public function register(array $params = []): void
    {
        AuthMiddleware::requireGuest();
        Request::verifyCsrf();

        $data = [
            'username'   => Request::post('username', ''),
            'email'      => Request::post('email', ''),
            'first_name' => Request::post('first_name', ''),
            'last_name'  => Request::post('last_name', ''),
            'contact'    => Request::post('contact', ''),
            'password'   => Request::postRaw('password', ''),
        ];

        $v = (new Validator())
            ->required('first_name', $data['first_name'], 'First name')
            ->minLen('first_name', $data['first_name'], 2, 'First name')
            ->required('last_name',  $data['last_name'],  'Last name')
            ->required('email',      $data['email'],      'Email')
            ->email('email', $data['email'])
            ->required('username', $data['username'], 'Username')
            ->minLen('username', $data['username'], 3, 'Username')
            ->maxLen('username', $data['username'], 50, 'Username')
            ->regex('username', $data['username'], '/^[a-zA-Z0-9_]+$/', 'Username may only contain letters, numbers, underscores.')
            ->required('password', $data['password'], 'Password')
            ->minLen('password', $data['password'], 6, 'Password');

        $errors = $v->errors();

        if ($v->passes()) {
            if (UserModel::existsByUsername($data['username'])) {
                $errors['username'] = "Username '{$data['username']}' is already taken.";
            }
            if (UserModel::existsByEmail($data['email'])) {
                $errors['email'] = "Email '{$data['email']}' is already registered.";
            }
        }

        if (!empty($errors)) {
            $_SESSION['old'] = $data;
            $this->render('auth/register', ['errors' => $errors, 'old' => $data]);
            return;
        }

        $id = UserModel::create($data);
        $user = UserModel::findById($id);

        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id'         => $user['id'],
            'username'   => $user['username'],
            'first_name' => $user['first_name'],
            'last_name'  => $user['last_name'],
            'email'      => $user['email'],
            'role'       => $user['role'],
            'photo_path' => $user['photo_path'],
        ];

        $this->flash('success', 'Registration successful! Welcome to e-Vartalap.');
        $this->redirect('/');
    }

    // ---- POST /auth/logout ----
    public function logout(array $params = []): void
    {
        Request::verifyCsrf();
        $_SESSION = [];
        session_destroy();
        $this->redirect('/?logout=1');
    }

    // ---- GET /auth/check-username (AJAX) ----
    public function checkUsername(array $params = []): void
    {
        $username  = Request::get('username', '');
        $available = !UserModel::existsByUsername($username);
        $this->json(['available' => $available]);
    }

    // ---- GET /auth/check-email (AJAX) ----
    public function checkEmail(array $params = []): void
    {
        $email     = Request::get('email', '');
        $available = !UserModel::existsByEmail($email);
        $this->json(['available' => $available]);
    }
}
