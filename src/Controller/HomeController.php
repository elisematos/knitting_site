<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\PatternRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param PatternRepository $patternRepository
     * @return Response
     */
    public function index(PatternRepository $patternRepository): Response
    {
        $latestPatterns = $patternRepository->findFourLatestPatterns();
        return $this->render('home/index.html.twig', [
            'latestPatterns' => $latestPatterns,
        ]);
    }
}
