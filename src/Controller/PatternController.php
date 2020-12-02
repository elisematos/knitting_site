<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Pattern;
use App\Entity\Yarn;
use App\Form\PatternType;
use App\Repository\PatternRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @Route("/admin/new", name="pattern_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $pattern = new Pattern();
        $form = $this->createForm(PatternType::class, $pattern);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pdfFile = $form->get('pdf')->getData();
            if ($pdfFile) {
                $originalFilename = pathinfo($pdfFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$pdfFile->guessExtension();
                try {
                    $pdfFile->move(
                        $this->getParameter('pdf_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $pattern->setPdfFilename($newFilename);
            }
            //Get uploaded images
            $images = $form->get('images')->getData();
            //Loop through images
            foreach($images as $image)
            {
                //Generate a new fileName
                $file = md5(uniqid()) . '.' . $image->guessExtension();
                //Save the file in the uploads directory
                $image->move(
                    $this->getParameter('images_directory'),
                    $file
                );
                //Save the image name in the database
                $img = new Image();
                $img->setName($file);
                $pattern->addImage($img);
            }
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
     * @Route("/admin/{id}/edit", name="pattern_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Pattern $pattern
     * @return Response
     */
    public function edit(Request $request, Pattern $pattern): Response
    {
        $form = $this->createForm(PatternType::class, $pattern);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Get uploaded images
            $images = $form->get('images')->getData();
            //Loop through images
            foreach($images as $image)
            {
                //Generate a new fileName
                $file = md5(uniqid()) . '.' . $image->guessExtension();
                //Save the file in the uploads directory
                $image->move(
                    $this->getParameter('images_directory'),
                    $file
                );
                //Save the image name in the database
                $img = new Image();
                $img->setName($file);
                $pattern->addImage($img);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('pattern_index');
        }

        return $this->render('pattern/edit.html.twig', [
            'pattern' => $pattern,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/{id}", name="pattern_delete", methods={"DELETE"})
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

    /**
     * @Route("/delete/image{id}", name="pattern_delete_image", methods={"DELETE"})
     * @param Image $image
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteImage(Image $image, Request $request)
    {
        $data = json_decode($request->getContent(), true);
        //Valid token check
        if($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token']))
        {
            //Get image name
            $name = $image->getName();
            //Delete file from uploads directory
            unlink($this->getParameter('images_directory').'/'.$name);
            //Delete database entry
            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();
            //Json response
            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token Invalide'], 401);
        }
    }
}
