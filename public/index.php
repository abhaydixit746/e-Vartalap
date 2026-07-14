<?php
/**
 * e-Vartalap PHP — Front Controller
 * All requests routed through here via .htaccess
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once dirname(__DIR__) . '/config/bootstrap.php';

use App\Core\Router;

$router = new Router();

// ---- Public routes ----
$router->get('/',                          'HomeController@index');
$router->get('/unanswered',                'HomeController@unanswered');

// ---- Auth ----
$router->get('/auth/login',                'AuthController@loginForm');
$router->post('/auth/login',               'AuthController@login');
$router->get('/auth/register',             'AuthController@registerForm');
$router->post('/auth/register',            'AuthController@register');
$router->post('/auth/logout',              'AuthController@logout');
$router->get('/auth/check-username',       'AuthController@checkUsername');
$router->get('/auth/check-email',          'AuthController@checkEmail');

// ---- Questions ----
$router->get('/questions',                 'QuestionController@index');
$router->get('/questions/unanswered',      'QuestionController@unanswered');
$router->get('/questions/my',              'QuestionController@my');
$router->get('/questions/ask',             'QuestionController@askForm');
$router->post('/questions/ask',            'QuestionController@ask');
$router->get('/questions/:id',             'QuestionController@show');
$router->post('/questions/:id/answer',     'QuestionController@submitAnswer');
$router->post('/questions/answers/:id/accept', 'QuestionController@acceptAnswer');

// ---- Users ----
$router->get('/users',                     'UserController@index');
$router->get('/users/:id',                 'UserController@show');
$router->get('/profile',                   'UserController@profileForm');
$router->post('/profile',                  'UserController@profileUpdate');

// ---- Admin ----
$router->get('/admin',                     'AdminController@dashboard');
$router->get('/admin/questions',           'AdminController@questions');
$router->post('/admin/questions/:id/approve', 'AdminController@approveQuestion');
$router->post('/admin/questions/:id/reject',  'AdminController@rejectQuestion');
$router->get('/admin/answers',             'AdminController@answers');
$router->post('/admin/answers/:id/approve',   'AdminController@approveAnswer');
$router->post('/admin/answers/:id/reject',    'AdminController@rejectAnswer');

// ---- Dispatch ----
$router->dispatch(
    $_SERVER['REQUEST_METHOD'],
    strtok($_SERVER['REQUEST_URI'], '?')
);
