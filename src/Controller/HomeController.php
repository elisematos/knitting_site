<?php

namespace App\Controller;

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
        $latestHatPattern = $patternRepository->findLatestPatternByCategory(1);
        $latestSweaterPattern = $patternRepository->findLatestPatternByCategory(8);
        $latestSockPattern = $patternRepository->findLatestPatternByCategory(5);
        $latestScarfPattern = $patternRepository->findLatestPatternByCategory(7);
        return $this->render('home/index.html.twig', [
            'latestPatterns' => $latestPatterns,
            'latestHatPattern' => $latestHatPattern,
            'latestSweaterPattern' => $latestSweaterPattern,
            'latestSockPattern' => $latestSockPattern,
            'latestScarfPattern' => $latestScarfPattern,
        ]);
    }
}
