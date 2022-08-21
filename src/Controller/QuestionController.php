<?php

namespace App\Controller;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class QuestionController extends AbstractController
{
    private $logger;
    private $isDebug;

    public function __construct(
        LoggerInterface $logger, bool $isDebug,
        private readonly QuestionRepository $repository,
    ){
        $this->logger = $logger;
        $this->isDebug = $isDebug;
    }


    #[Route('/', name: 'app_homepage')]
    public function homepage(Request $request): Response
    {
        $queryBuilder = $this->repository->creatQBForQuestionsOrderedByAskedAt();

        $pager = new Pagerfanta(new QueryAdapter($queryBuilder));
        $pager->setMaxPerPage(5);
        $pager->setCurrentPage($request->query->get('page', 1));

        return $this->render('question/homepage.html.twig', [
            'questions' => $pager
        ]);
    }


    #[Route('/questions/new')]
    public function create(SluggerInterface $slugger): Response {
        dd('0k');

//        return new Response(sprintf('Question #%d added with the slug %s', $question->getId(), $question->getSlug()));
    }



    #[Route('/questions/{slug}', name: 'app_question_show')]
    public function show(Question $question): Response
    {
        if ($this->isDebug) {
            $this->logger->info('We are in debug mode!');
        }

        return $this->render('question/show.html.twig', [
            'question' => $question,
        ]);
    }

    #[Route('/questions/{slug}/vote', name: 'app_question_vote')]
    public function updateVote(Question $question, Request $request, EntityManagerInterface $em) {
        $direction = $request->request->get('direction');
        if($direction === 'up') {
            $question->upVote();
        } else {
            $question->downVote();
        }
        $em->flush();

        return $this->redirectToRoute('app_question_show', [
            'slug' => $question->getSlug()
        ]);
    }
}
