<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Repository\AnswerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnswerController extends AbstractController
{

    #[Route('/answers/{id}/vote', name: 'app_answer_vote')]
    public function vote(Answer $answer, Request $request) {
    }

    #[Route('/answers/bests', name: 'app_answer_bests')]
    public function bests(AnswerRepository $repository): Response {
        $answers = $repository->findBestAnswers();
        return $this->render('answer/bests.html.twig', [
            'answers' => $answers
        ]);
    }

}
