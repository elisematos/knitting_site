<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Pattern;
use App\Form\PatternType;
use App\Form\SearchPatternType;
use App\Repository\PatternRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 *
 * @Route("/pattern")
 */
class PatternController extends AbstractController
{
    /**
     * @Route("/", name="pattern_index", methods={"GET"})
     * @param PatternRepository $patternRepository
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function index(PatternRepository $patternRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $searchBarUsed = false;
        $form = $this->createForm(SearchPatternType::class);
        $search = $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            //Search patterns containing key words
            $patterns = $patternRepository->search(
                $search->get('keyWord')->getData(),
                $search->get('category')->getData(),
                $search->get('skillLevel')->getData()
            );
            $searchBarUsed = true;
        } else {
            $patterns = $paginator->paginate(
            $patternRepository->findPatternsByDateDESCQuery(),
            $request->query->getInt('page', 1),
            8
        );}
        return $this->render('pattern/index.html.twig', [
            'patterns' => $patterns,
            'form' => $form->createView(),
            'searchBarUsed' => $searchBarUsed
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
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
            $pdfFile = $form->get('pdf')->getData();
            if ($pdfFile) {
                $originalFilename = pathinfo($pdfFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate(
                    'Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',
                    $originalFilename
                );
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
            $images = $form->get('images')->getData();
            $pattern = $this->setImages($images, $pattern);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($pattern);
            $entityManager->flush();
            $this->addFlash('success', 'Nouveau patron ajouté au catalogue avec succès.');

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
     * @IsGranted("ROLE_ADMIN")
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
            //Get uploaded images
            $this->setImages($form->get('images')->getData(), $pattern);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Les modifications apportées ont été enregistrées avec succès.');

            return $this->redirectToRoute('pattern_index');
        }

        return $this->render('pattern/edit.html.twig', [
            'pattern' => $pattern,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN")
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
            $this->addFlash('success', 'Patron supprimé du catalogue avec succès.');
        }

        return $this->redirectToRoute('pattern_index');
    }

    /**
     * @IsGranted("ROLE_ADMIN")
     * @Route("/delete/image{id}", name="pattern_delete_image", methods={"DELETE"})
     * @param Image $image
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteImage(Image $image, Request $request): JsonResponse
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

    /**
     * Get uploaded images
     * @param $images
     * @param $pattern
     * @return Pattern
     */
    private function setImages($images , $pattern): Pattern
    {
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
        return $pattern;
    }
}
