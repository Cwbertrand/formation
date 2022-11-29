<?php

namespace App\Controller;

use App\Entity\Programme;
use App\Form\ProgrammeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProgrammeController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    #[Route('/programme', name: 'programme')]
    public function index(): Response
    {
        return $this->render('programme/index.html.twig', [
            
        ]);
    }

    #[Route('/programme/add', name: 'add_programme')]
    public function addProgramme(Programme $programme = null, Request $request)
    {
        $form = $this->createForm(ProgrammeType::class, $programme);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $programme = $form->getData();
            $this->em->persist($programme);
            $this->em->flush();

            return $this->redirectToRoute('instituleSession');
        }
        
        return $this->render('programme/addprogramme.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
