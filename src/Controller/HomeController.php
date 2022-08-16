<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
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
    public function browse(HttpClientInterface $client, string $slug = null) {
        $response = $client->request('GET', 'https://raw.githubusercontent.com/SymfonyCasts/vinyl-mixes/main/mixes.json');
        $mixes = $response->toArray();

        $genre = u($slug)->replace('-', ' ')->title(true) ?: null;

        return $this->render('home/browse.html.twig', [
            'mixes' => $mixes,
            'genre' => $genre,
        ]);
    }

    #[Route('/show/{name?}')]
    public function show($name) {
        $name = !!$name ? $name : 'wold';
        $name = u($name)->title();
        return new Response('Hello ' . $name);
    }

    private function getMixes() {
        return [
            [
                'title' => 'PB & James',
                'tranckCount' => 14,
                'genre' => 'Rock',
                'createdAt' => new \DateTime('2021-10-02'),
            ],
            [
                'title' => 'In love with you',
                'tranckCount' => 8,
                'genre' => 'Heavy Metal',
                'createdAt' => new \DateTime('2022-04-28'),
            ],
            [
                'title' => 'Sprics grills',
                'tranckCount' => 10,
                'genre' => 'Pop',
                'createdAt' => new \DateTime('2019-05-21'),
            ]
        ];
    }

}