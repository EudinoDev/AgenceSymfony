<?php

namespace App\Controller;

use App\Repository\PropertyRepository;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @var Twig\Environment
     */
    private $twig;


    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }



    /**
     * @Route("/", name="homePageShow")
     * @param PropertyRepository $repository
     * @return Response
     */
    public function index(PropertyRepository $repository): Response
    {
        $properties = $repository->findLatest();

        return $this->render('pages/homePageShow.html.twig', [
            'properties' => $properties,
            'actif_menu' => 'home'
        ]);
    }
}
