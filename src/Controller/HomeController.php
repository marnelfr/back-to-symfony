<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\String\u;

class HomeController extends AbstractController
{

    #[Route('/', name: 'home')]
    public function index(): Response {
        $tracks = [
            ['title' => "Gangsta's Paradise", 'author' => 'Coolio'],
            ['title' => 'Waterfalls', 'author' => 'TLC'],
            ['title' => 'Creep', 'author' => 'TLC'],
            ['title' => 'Kiss from a Rose', 'author' => 'Seal'],
            ['title' => 'On Bended Knee', 'author' => 'Boyz II Men'],
            ['title' => 'Another Night', 'author' => 'Real McCoy'],
            ['title' => 'Fantasy', 'author' => 'Mariah Carey'],
            ['title' => 'Take a Bow', 'author' => 'Madonna'],
        ];

        return $this->render('home/index.html.twig', [
            'tracks' => $tracks
        ]);
    }

    #[Route('browse/{slug?}', name: 'browse')]
    public function browse(string $slug = null) {
        $genres = [
            'pop', 'rock', 'heavy-metal'
        ];
        $actual = u($slug)->replace('-', ' ')->title(true) ?: null;

        return $this->render('home/browse.html.twig', [
            'genres' => $genres,
            'actual' => $actual,
        ]);
    }

    #[Route('/show/{name?}')]
    public function show($name) {
        $name = !!$name ? $name : 'wold';
        $name = u($name)->title();
        return new Response('Hello ' . $name);
    }

}