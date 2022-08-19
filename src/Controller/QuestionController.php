<?php

namespace App\Controller;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class QuestionController extends AbstractController
{
    private $logger;
    private $isDebug;

    public function __construct(
        LoggerInterface $logger, bool $isDebug,
        private readonly QuestionRepository $repository
    ){
        $this->logger = $logger;
        $this->isDebug = $isDebug;
    }


    #[Route('/', name: 'app_homepage')]
    public function homepage(): Response
    {
        $questions = $this->repository->findAskedOrderedByAskedAt();

        return $this->render('question/homepage.html.twig', [
            'questions' => $questions
        ]);
    }


    #[Route('/questions/new')]
    public function create(SluggerInterface $slugger): Response {
        $question = new Question();
        $question->setName('Missing name')
            ->setSlug($slugger->slug('missing name ' . rand(1, 1000)))
            ->setQuestion(
<<<EOF
Mv stands for move. mv is used to move one or more files or directories from one place to another in a file system like UNIX. It has two distinct functions: 
(i) It renames a file or folder. 
(ii) It moves a group of files to a different directory. 
No additional space is consumed on a disk during renaming. This command normally works silently means no prompt for confirmation.
EOF
            )
            ->setAskedAt(new \DateTimeImmutable(sprintf('-%d days', rand(1, 100))))
            ->setVotes(rand(-20, 30))
        ;

        $this->repository->add($question, true);

        return new Response(sprintf('Question #%d added with the slug %s', $question->getId(), $question->getSlug()));
    }



    #[Route('/questions/{slug}', name: 'app_question_show')]
    public function show(Question $question): Response
    {
        if ($this->isDebug) {
            $this->logger->info('We are in debug mode!');
        }

        $answers = [
            'Make sure your cat is sitting `purrrfectly` still 🤣',
            'Honestly, I like furry shoes better than MY cat',
            'Maybe... try saying the spell backwards?',
        ];

        return $this->render('question/show.html.twig', [
            'question' => $question,
            'answers' => $answers,
        ]);
    }
}
