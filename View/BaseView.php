<?php

require_once("Settings.php");

abstract class BaseView {

    public static function redirectToErrorPage() {
        header("Location: /". Settings::$ROOT_PATH . "/error.html");
    }   

    protected $messageALocation = "CookieValueA";
    protected $messageBLocation = "CookieValueB";
    protected $messageCLocation = "CookieValueC";
    protected $id = 'id';

    protected $showAllQuizToPlayLocation = 'showAllQuizToPlay';
    protected $showAllQuizLocation = 'showAllQuiz';

    protected $showQuizLocation = 'showQuiz';
    protected $createQuizLocation = 'createQuiz';
    protected $playQuizLocation = 'playQuiz';

    protected $showQuestionLocation = 'showQuestion';
    protected $addQuestionLocation = 'addQuestion';

    protected $messageRightAnswerLocation = "CookieRightAnswer";
    protected $removeAnswersLocation = 'removeAnswers';
    protected $addAnswersLocation = "addAnswers";
    
    protected $messageLocation = "CookieMessage";
    protected $alphabets = array('A', 'B', 'C');
    protected $showResultsLocation = "showResults";

    protected $logOutLocation = 'logOut';
    
}