<?php
namespace App\Controller;

use App\Core\Request;
use App\Middleware\AuthMiddleware;
use App\Model\AnswerModel;
use App\Model\QuestionModel;
use App\Util\Validator;

class QuestionController extends BaseController
{
    // ---- GET /questions ----
    public function index(array $params = []): void
    {
        $page = $this->page();
        $data = QuestionModel::getApproved($page, CFG['app']['page_size']);
        $this->render('question/list', [
            'questions' => $data,
            'pageTitle' => 'Questions',
        ]);
    }

    // ---- GET /questions/unanswered ----
    public function unanswered(array $params = []): void
    {
        $page = $this->page();
        $data = QuestionModel::getUnanswered($page, CFG['app']['page_size']);
        $this->render('question/list', [
            'questions' => $data,
            'pageTitle' => 'Unanswered Questions',
        ]);
    }

    // ---- GET /questions/my ----
    public function my(array $params = []): void
    {
        AuthMiddleware::requireLogin();
        $page = $this->page();
        $uid  = (int) $this->currentUser()['id'];
        $data = QuestionModel::getByAuthor($uid, $page, CFG['app']['page_size']);
        $this->render('question/list', [
            'questions' => $data,
            'pageTitle' => 'My Questions',
        ]);
    }

    // ---- GET /questions/:id ----
    public function show(array $params = []): void
    {
        $id       = (int) ($params['id'] ?? 0);
        $question = QuestionModel::findById($id);

        if (!$question) {
            http_response_code(404);
            include VIEWS . '/error/404.php';
            return;
        }

        $isAdmin = $this->isAdmin();

        // Non-approved visible to admin only
        if ($question['status'] !== 'APPROVED' && !$isAdmin) {
            http_response_code(404);
            include VIEWS . '/error/404.php';
            return;
        }

        QuestionModel::incrementViewCount($id);
        $tags    = QuestionModel::getTags($id);
        $answers = AnswerModel::getForQuestion($id, $isAdmin);

        $this->render('question/detail', [
            'question'      => $question,
            'tags'          => $tags,
            'answers'       => $answers,
            'isAdmin'       => $isAdmin,
            'isLoggedIn'    => isLoggedIn(),
            'currentUser'   => $this->currentUser(),
            'answerError'   => Request::getFlash('answerError'),
            'answerOld'     => $_SESSION['answerOld'] ?? '',
        ]);
        unset($_SESSION['answerOld']);
    }

    // ---- GET /questions/ask ----
    public function askForm(array $params = []): void
    {
        AuthMiddleware::requireLogin();
        $this->render('question/ask', [
            'errors' => [],
            'old'    => $_SESSION['old'] ?? [],
        ]);
        unset($_SESSION['old']);
    }

    // ---- POST /questions/ask ----
    public function ask(array $params = []): void
    {
        AuthMiddleware::requireLogin();
        Request::verifyCsrf();

        $title = Request::post('title', '');
        $body  = Request::postRaw('body', '');
        $tags  = Request::post('tags', '');

        $v = (new Validator())
            ->required('title', $title, 'Question title')
            ->minLen('title', $title, 10, 'Title')
            ->maxLen('title', $title, 500, 'Title');

        if (!$v->passes()) {
            $_SESSION['old'] = compact('title', 'body', 'tags');
            $this->render('question/ask', ['errors' => $v->errors(), 'old' => $_SESSION['old']]);
            return;
        }

        $uid = (int) $this->currentUser()['id'];
        QuestionModel::create($uid, $title, $body, $tags);

        $this->flash('success', 'Your question has been submitted and is pending admin approval.');
        $this->redirect('/');
    }

    // ---- POST /questions/:id/answer ----
    public function submitAnswer(array $params = []): void
    {
        AuthMiddleware::requireLogin();
        Request::verifyCsrf();

        $questionId = (int) ($params['id'] ?? 0);
        $body       = Request::postRaw('body', '');

        $v = (new Validator())
            ->required('body', $body, 'Answer')
            ->minLen('body', $body, 10, 'Answer');

        if (!$v->passes()) {
            $_SESSION['answerOld'] = $body;
            $this->flash('answerError', $v->firstError());
            $this->redirect('/questions/' . $questionId);
            return;
        }

        $question = QuestionModel::findById($questionId);
        if (!$question || $question['status'] !== 'APPROVED') {
            $this->flash('error', 'Cannot answer this question.');
            $this->redirect('/');
            return;
        }

        $uid = (int) $this->currentUser()['id'];
        AnswerModel::create($uid, $questionId, $body);

        $this->flash('success', 'Your answer has been submitted and is pending admin approval.');
        $this->redirect('/questions/' . $questionId);
    }

    // ---- POST /questions/answers/:id/accept ----
    public function acceptAnswer(array $params = []): void
    {
        AuthMiddleware::requireLogin();
        Request::verifyCsrf();

        $answerId   = (int) ($params['id'] ?? 0);
        $questionId = (int) Request::post('question_id', 0);

        $answer = AnswerModel::findById($answerId);
        if (!$answer) {
            $this->flash('error', 'Answer not found.');
            $this->redirect('/questions/' . $questionId);
            return;
        }

        // Only the question author may accept
        if ((int) $answer['question_author_id'] !== (int) $this->currentUser()['id']) {
            $this->flash('error', 'Only the question author can accept an answer.');
            $this->redirect('/questions/' . $questionId);
            return;
        }

        AnswerModel::setAccepted($answerId);
        $this->flash('success', 'Answer marked as accepted.');
        $this->redirect('/questions/' . $questionId);
    }
}
