<?php

namespace App\Controller;

use App\Entity\Formateur;
use App\Entity\InstituleSession;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em = $em;
    }
    
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        
        $sessionEnCour = $this->em->getRepository(InstituleSession::class)->sessionEnCour();
        $sessionToCome = $this->em->getRepository(InstituleSession::class)->sessionToCome();
        $sessionPassed = $this->em->getRepository(InstituleSession::class)->sessionPassed();

        return $this->render('home/index.html.twig', [
            'sessionEnCours' => $sessionEnCour,
            'sessionToComes' => $sessionToCome,
            'sessionPasseds' => $sessionPassed,
        ]);
    }

    #[Route('/institulesession/{id}', name: 'show_instituleSession')]
    public function showSession(InstituleSession $instituleSession): Response
    {
        return $this->render('institule_session/showsession.html.twig', [
            'sessiondetails' => $instituleSession
        ]);
    }
}
