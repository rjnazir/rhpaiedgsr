<?php

namespace App\Controller;

use App\Entity\DecisionsPromotions;
use App\Form\DecisionsPromotionsType;
use App\Repository\DecisionsPromotionsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/decisions/promotions")
 */
class DecisionsPromotionsController extends AbstractController
{
    /**
     * @Route("/", name="decisions_promotions_index", methods={"GET"})
     */
    public function index(DecisionsPromotionsRepository $decisionsPromotionsRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $decisionsPromotionsRepository->findBy([], ["id" => "DESC"]), /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        ); 
        return $this->render('decisions_promotions/index.html.twig', [
            'decisions_promotions' => $pagination,
        ]);
    }

    /**
     * @Route("/new", name="decisions_promotions_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $decisionsPromotion = new DecisionsPromotions();
        $form = $this->createForm(DecisionsPromotionsType::class, $decisionsPromotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($decisionsPromotion);
            $entityManager->flush();

            return $this->redirectToRoute('decisions_promotions_index');
        }

        return $this->render('decisions_promotions/new.html.twig', [
            'decisions_promotion' => $decisionsPromotion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="decisions_promotions_show", methods={"GET"})
     */
    public function show(DecisionsPromotions $decisionsPromotion): Response
    {
        return $this->render('decisions_promotions/show.html.twig', [
            'decisions_promotion' => $decisionsPromotion,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="decisions_promotions_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, DecisionsPromotions $decisionsPromotion): Response
    {
        $form = $this->createForm(DecisionsPromotionsType::class, $decisionsPromotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('decisions_promotions_index');
        }

        return $this->render('decisions_promotions/edit.html.twig', [
            'decisions_promotion' => $decisionsPromotion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="decisions_promotions_delete", methods={"DELETE"})
     */
    public function delete(Request $request, DecisionsPromotions $decisionsPromotion): Response
    {
        if ($this->isCsrfTokenValid('delete'.$decisionsPromotion->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($decisionsPromotion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('decisions_promotions_index');
    }
}
