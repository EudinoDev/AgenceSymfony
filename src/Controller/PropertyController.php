<?php

namespace App\Controller;

use Twig\Environment;
use App\Entity\Property;
use App\Repository\PropertyRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PropertyController extends AbstractController
{
    /**
     * @var PropertyRepository
     */
    private $repository;

    // injection de $repository
    public function __construct(PropertyRepository $repository)
    {
        $this->repository = $repository;
    }




    /**
     * @Route("/biens", name="property.index")
     * @return Response
     */
    public function index(PropertyRepository $repository): Response
    {
        return $this->render('property/propertyIndexShow.html.twig', [
            'actif_menu' => 'properties'
        ]);
    }

    /**
     * @Route("biens/{slug}-{id}", name="property.show", requirements={"slug": "[a-z0-9\-]*"})
     */
    public function show($slug, $id): Response
    {
        $property = $this->repository->find($id);

        return $this->render("property/propertyShowDetails.html.twig", [
            'property' => $property,
            'actif_menu' => 'properties'
        ]);
    }
}
