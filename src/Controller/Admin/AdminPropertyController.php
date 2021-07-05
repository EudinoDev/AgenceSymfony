<?php

namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminPropertyController extends AbstractController
{
    /**
     * @var PropertyRepository $repository
     */
    private $repository;

    /**
     * @var ObjectManager $em
     */
    private $entityManager;

    public function __construct(PropertyRepository $repository, ObjectManager $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/admin", name="admin.property.index")
     */
    public function index()
    {
        $properties = $this->repository->findAll();

        return $this->render("admin/property/indexShowAdminMode.html.twig", [
            'properties' => $properties
        ]);
    }

    /**
     * @Route("/admin/create/property/new", name="admin.property.create.new")
     */
    public function new(Request $request)
    {
        $property = new Property();

        $form = $this->createForm(PropertyType::class, $property);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($property);
            $this->entityManager->flush();

            $this->addFlash('success', "Action : CRÉE AVEC SUCCES");

            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render("admin/property/newShowAdminMode.html.twig", [
            'property_to_edit' => $property,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/edit/property/{id}", name="admin.property.edit", methods="GET|POST")
     * @param Property $property
     * @param Request $request
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function edit(Property $property, Request $request): Response
    {
        $form = $this->createForm(PropertyType::class, $property);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            $this->addFlash('success', "Action : MIS A JOUR AVEC SUCCES");
            return $this->redirectToRoute("admin.property.index");
        }


        return $this->render("admin/property/editShowAdminMode.html.twig", [
            'property' => $property,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/admin/delete/property/{id}", name="admin.property.delete", methods="GET|POST|DELETE")
     * @param Property $property
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function delete(Property $property, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $property->getId(), $request->get('_token'))) {
            $this->entityManager->remove($property);
            $this->entityManager->flush();
            $this->addFlash('success', "Action : SUPPRIMÉ AVEC SUCCES");
        }

        return $this->redirectToRoute('admin.property.index');
    }
}
