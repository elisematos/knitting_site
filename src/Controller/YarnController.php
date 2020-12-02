<?php

namespace App\Controller;

use App\Entity\Yarn;
use App\Form\YarnType;
use App\Repository\YarnRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/yarn")
 */
class YarnController extends AbstractController
{
    /**
     * @Route("/", name="yarn_index", methods={"GET"})
     * @param YarnRepository $yarnRepository
     * @return Response
     */
    public function index(YarnRepository $yarnRepository): Response
    {
        return $this->render('yarn/index.html.twig', [
            'yarns' => $yarnRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="yarn_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $yarn = new Yarn();
        $form = $this->createForm(YarnType::class, $yarn);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($yarn);
            $entityManager->flush();

            return $this->redirectToRoute('yarn_index');
        }

        return $this->render('yarn/new.html.twig', [
            'yarn' => $yarn,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="yarn_show", methods={"GET"})
     * @param Yarn $yarn
     * @return Response
     */
    public function show(Yarn $yarn): Response
    {
        return $this->render('yarn/show.html.twig', [
            'yarn' => $yarn,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="yarn_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Yarn $yarn
     * @return Response
     */
    public function edit(Request $request, Yarn $yarn): Response
    {
        $form = $this->createForm(YarnType::class, $yarn);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('yarn_index');
        }

        return $this->render('yarn/edit.html.twig', [
            'yarn' => $yarn,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="yarn_delete", methods={"DELETE"})
     * @param Request $request
     * @param Yarn $yarn
     * @return Response
     */
    public function delete(Request $request, Yarn $yarn): Response
    {
        if ($this->isCsrfTokenValid('delete'.$yarn->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($yarn);
            $entityManager->flush();
        }

        return $this->redirectToRoute('yarn_index');
    }
}
