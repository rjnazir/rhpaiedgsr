<?php

namespace App\Controller;

use App\Entity\Diplomes;
use App\Form\DiplomesType;
use App\Repository\DiplomesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/diplomes")
 */
class DiplomesController extends AbstractController
{
    /**
     * @Route("/", name="diplomes_index", methods={"GET"})
     */
    public function index(DiplomesRepository $diplomesRepository): Response
    {
        return $this->render('diplomes/index.html.twig', [
            'diplomes' => $diplomesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="diplomes_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $diplome = new Diplomes();
        $form = $this->createForm(DiplomesType::class, $diplome);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($diplome);
            $entityManager->flush();

            return $this->redirectToRoute('diplomes_index');
        }

        return $this->render('diplomes/new.html.twig', [
            'diplome' => $diplome,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="diplomes_show", methods={"GET"})
     */
    public function show(Diplomes $diplome): Response
    {
        return $this->render('diplomes/show.html.twig', [
            'diplome' => $diplome,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="diplomes_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Diplomes $diplome): Response
    {
        $form = $this->createForm(DiplomesType::class, $diplome);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('diplomes_index');
        }

        return $this->render('diplomes/edit.html.twig', [
            'diplome' => $diplome,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="diplomes_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Diplomes $diplome): Response
    {
        if ($this->isCsrfTokenValid('delete'.$diplome->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($diplome);
            $entityManager->flush();
        }

        return $this->redirectToRoute('diplomes_index');
    }
}