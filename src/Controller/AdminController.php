<?php
namespace App\Controller;

use App\Core\Request;
use App\Middleware\AuthMiddleware;
use App\Model\AnswerModel;
use App\Model\QuestionModel;

class AdminController extends BaseController
{
    // ---- GET /admin ----
    public function dashboard(array $params = []): void
    {
        AuthMiddleware::requireAdmin();
        $this->render('admin/dashboard', [
            'pendingQuestions' => QuestionModel::countPending(),
            'pendingAnswers'   => AnswerModel::countPending(),
            'success'          => Request::getFlash('success'),
        ]);
    }

    // ---- GET /admin/questions ----
    public function questions(array $params = []): void
    {
        AuthMiddleware::requireAdmin();
        $page = $this->page();
        $data = QuestionModel::getAll($page, CFG['app']['page_size']);
        $this->render('admin/questions', [
            'questions' => $data,
            'success'   => Request::getFlash('success'),
        ]);
    }

    // ---- POST /admin/questions/:id/approve ----
    public function approveQuestion(array $params = []): void
    {
        AuthMiddleware::requireAdmin();
        Request::verifyCsrf();
        $id = (int) ($params['id'] ?? 0);
        QuestionModel::setStatus($id, 'APPROVED');
        $this->flash('success', 'Question approved successfully.');
        $this->redirect('/admin/questions');
    }

    // ---- POST /admin/questions/:id/reject ----
    public function rejectQuestion(array $params = []): void
    {
        AuthMiddleware::requireAdmin();
        Request::verifyCsrf();
        $id = (int) ($params['id'] ?? 0);
        QuestionModel::setStatus($id, 'REJECTED');
        $this->flash('success', 'Question rejected.');
        $this->redirect('/admin/questions');
    }

    // ---- GET /admin/answers ----
    public function answers(array $params = []): void
    {
        AuthMiddleware::requireAdmin();
        $page = $this->page();
        $data = AnswerModel::getPending($page, CFG['app']['page_size']);
        $this->render('admin/answers', [
            'answers' => $data,
            'success' => Request::getFlash('success'),
        ]);
    }

    // ---- POST /admin/answers/:id/approve ----
    public function approveAnswer(array $params = []): void
    {
        AuthMiddleware::requireAdmin();
        Request::verifyCsrf();
        $id = (int) ($params['id'] ?? 0);
        AnswerModel::setStatus($id, 'APPROVED');
        $this->flash('success', 'Answer approved successfully.');
        $this->redirect('/admin/answers');
    }

    // ---- POST /admin/answers/:id/reject ----
    public function rejectAnswer(array $params = []): void
    {
        AuthMiddleware::requireAdmin();
        Request::verifyCsrf();
        $id = (int) ($params['id'] ?? 0);
        AnswerModel::setStatus($id, 'REJECTED');
        $this->flash('success', 'Answer rejected.');
        $this->redirect('/admin/answers');
    }
}
