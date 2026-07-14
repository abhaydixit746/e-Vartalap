<?php
namespace App\Controller;

use App\Core\Request;
use App\Model\QuestionModel;

class HomeController extends BaseController
{
    public function index(array $params = []): void
    {
        $page    = $this->page();
        $keyword = Request::get('keyword', '');
        $perPage = CFG['app']['page_size'];

        if ($keyword !== '') {
            $data = QuestionModel::search($keyword, $page, $perPage);
            $pageTitle = 'Search: ' . $keyword;
        } else {
            $data = QuestionModel::getApproved($page, $perPage);
            $pageTitle = 'Latest Questions';
        }

        $this->render('index', [
            'questions' => $data,
            'keyword'   => $keyword,
            'pageTitle' => $pageTitle,
        ]);
    }

    public function unanswered(array $params = []): void
    {
        $page = $this->page();
        $data = QuestionModel::getUnanswered($page, CFG['app']['page_size']);
        $this->render('index', [
            'questions' => $data,
            'pageTitle' => 'Unanswered Questions',
            'keyword'   => '',
        ]);
    }
}
