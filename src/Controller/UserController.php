<?php
namespace App\Controller;

use App\Core\Request;
use App\Middleware\AuthMiddleware;
use App\Model\UserModel;
use App\Util\FileUpload;
use App\Util\Validator;

class UserController extends BaseController
{
    // ---- GET /users ----
    public function index(array $params = []): void
    {
        $page    = $this->page();
        $perPage = 20;

        if ($this->currentUser()) {
            $data = UserModel::getAllActiveExcept((int) $this->currentUser()['id'], $page, $perPage);
        } else {
            $data = UserModel::getAllActive($page, $perPage);
        }

        $this->render('user/list', ['users' => $data]);
    }

    // ---- GET /users/:id ----
    public function show(array $params = []): void
    {
        $id   = (int) ($params['id'] ?? 0);
        $user = UserModel::findById($id);
        if (!$user) {
            http_response_code(404);
            include VIEWS . '/error/404.php';
            return;
        }
        $this->render('user/view', ['profile' => $user]);
    }

    // ---- GET /profile ----
    public function profileForm(array $params = []): void
    {
        AuthMiddleware::requireLogin();
        $user = UserModel::findById((int) $this->currentUser()['id']);
        $this->render('user/profile', [
            'profile' => $user,
            'errors'  => [],
            'success' => Request::getFlash('success'),
        ]);
    }

    // ---- POST /profile ----
    public function profileUpdate(array $params = []): void
    {
        AuthMiddleware::requireLogin();
        Request::verifyCsrf();

        $uid  = (int) $this->currentUser()['id'];
        $user = UserModel::findById($uid);

        $data = [
            'first_name'  => Request::post('first_name', ''),
            'last_name'   => Request::post('last_name', ''),
            'email'       => Request::post('email', ''),
            'contact'     => Request::post('contact', ''),
            'company'     => Request::post('company', ''),
            'designation' => Request::post('designation', ''),
        ];

        $v = (new Validator())
            ->required('first_name', $data['first_name'], 'First name')
            ->required('last_name',  $data['last_name'],  'Last name')
            ->required('email',      $data['email'],      'Email')
            ->email('email', $data['email']);

        $errors = $v->errors();

        // Check email uniqueness only if changed
        if ($v->passes() && strtolower($data['email']) !== strtolower($user['email'])) {
            if (UserModel::existsByEmail($data['email'])) {
                $errors['email'] = 'Email is already in use by another account.';
            }
        }

        if (!empty($errors)) {
            $this->render('user/profile', ['profile' => array_merge($user, $data), 'errors' => $errors]);
            return;
        }

        // Handle photo upload
        $photo = Request::file('photo');
        if ($photo && $photo['error'] === UPLOAD_ERR_OK && $photo['size'] > 0) {
            try {
                $photoPath = FileUpload::storePhoto($photo);
                if ($user['photo_path']) FileUpload::deletePhoto($user['photo_path']);
                UserModel::updatePhoto($uid, $photoPath);
                // Update session
                $_SESSION['user']['photo_path'] = $photoPath;
            } catch (\RuntimeException $e) {
                $errors['photo'] = $e->getMessage();
                $this->render('user/profile', ['profile' => array_merge($user, $data), 'errors' => $errors]);
                return;
            }
        }

        UserModel::update($uid, $data);

        // Refresh session data
        $_SESSION['user']['first_name'] = $data['first_name'];
        $_SESSION['user']['last_name']  = $data['last_name'];
        $_SESSION['user']['email']      = $data['email'];

        $this->flash('success', 'Profile updated successfully.');
        $this->redirect('/profile');
    }
}
