<?php

namespace App\Controller;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use App\Service\MarkdownHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class QuestionController extends AbstractController
{
    private $logger;
    private $isDebug;

    public function __construct(LoggerInterface $logger, bool $isDebug)
    {
        $this->logger = $logger;
        $this->isDebug = $isDebug;
    }


    #[Route('/', name: 'app_homepage')]
    public function homepage()
    {
        return $this->render('question/homepage.html.twig');
    }

    #[Route('/questions/new')]
    public function create(SluggerInterface $slugger, EntityManagerInterface $em) {
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
            )->setAskedAt(new \DateTimeImmutable(sprintf('-%d days', rand(1, 100))));
        $em->persist($question);
        $em->flush();

        return new Response(sprintf('Question #%d added with the slug %s', $question->getId(), $question->getSlug()));
    }

    #[Route('/questions/{slug}', name: 'app_question_show')]
    public function show($slug, MarkdownHelper $markdownHelper, QuestionRepository $repository)
    {
        if ($this->isDebug) {
            $this->logger->info('We are in debug mode!');
        }

        /** @var Question|null $question */
        $question = $repository->findOneBy(['slug' => $slug]);
        if(!$question) {
            throw $this->createNotFoundException(sprintf('Question of slug %s not found', $slug));
        }
        dd($question);

        $answers = [
            'Make sure your cat is sitting `purrrfectly` still 🤣',
            'Honestly, I like furry shoes better than MY cat',
            'Maybe... try saying the spell backwards?',
        ];
        $questionText = 'I\'ve been turned into a cat, any *thoughts* on how to turn back? While I\'m **adorable**, I don\'t really care for cat food.';

        $parsedQuestionText = $markdownHelper->parse($questionText);

        return $this->render('question/show.html.twig', [
            'question' => ucwords(str_replace('-', ' ', $slug)),
            'questionText' => $parsedQuestionText,
            'answers' => $answers,
        ]);
    }
}
