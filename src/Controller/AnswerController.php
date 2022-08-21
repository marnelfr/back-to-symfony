<?php

namespace App\Controller;

use App\Entity\Answer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnswerController extends AbstractController
{

    #[Route('/answers/{id}/vote', name: 'app_answer_vote')]
    public function vote(Answer $answer, Request $request) {
    }

}
