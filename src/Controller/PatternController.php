<?php

namespace App\Controller;

use App\Entity\Pattern;
use App\Entity\Yarn;
use App\Form\PatternType;
use App\Repository\PatternRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/pattern")
 */
class PatternController extends AbstractController
{
    /**
     * @Route("/", name="pattern_index", methods={"GET"})
     * @param PatternRepository $patternRepository
     * @return Response
     */
    public function index(PatternRepository $patternRepository): Response
    {
        return $this->render('pattern/index.html.twig', [
            'patterns' => $patternRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="pattern_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $pattern = new Pattern();
        $form = $this->createForm(PatternType::class, $pattern);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pattern);
            $entityManager->flush();

            return $this->redirectToRoute('pattern_index');
        }

        return $this->render('pattern/new.html.twig', [
            'pattern' => $pattern,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="pattern_show", methods={"GET"})
     * @param Pattern $pattern
     * @return Response
     */
    public function show(Pattern $pattern): Response
    {
        return $this->render('pattern/show.html.twig', [
            'pattern' => $pattern,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="pattern_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Pattern $pattern
     * @return Response
     */
    public function edit(Request $request, Pattern $pattern): Response
    {
        $form = $this->createForm(PatternType::class, $pattern);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('pattern_index');
        }

        return $this->render('pattern/edit.html.twig', [
            'pattern' => $pattern,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="pattern_delete", methods={"DELETE"})
     * @param Request $request
     * @param Pattern $pattern
     * @return Response
     */
    public function delete(Request $request, Pattern $pattern): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pattern->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($pattern);
            $entityManager->flush();
        }

        return $this->redirectToRoute('pattern_index');
    }
}
