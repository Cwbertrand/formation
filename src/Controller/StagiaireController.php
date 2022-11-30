<?php

namespace App\Controller;

use App\Form\ModuleType;
use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StagiaireController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    #[Route('/stagiaire', name: 'stagiaire')]
    public function index(): Response
    {
        $form = $this->em->getRepository(Stagiaire::class)->findBy([], ['nom' => 'ASC']);
        return $this->render('stagiaire/index.html.twig', [
            'forms' => $form,
        ]);
    }

    #[Route('/stagiaire/add', name: 'add_stagiaire')]
    #[Route('/stagiaire/{id}/edit', name: 'edit_stagiaire')]
    public function addModule(Stagiaire $stagiaire = null, Request $request)
    {
        if(!$stagiaire){
            $stagiaire = new Stagiaire();
        }

        $form = $this->createForm(StagiaireType::class, $stagiaire);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $stagiaire = $form->getData();
            $this->em->persist($stagiaire);
            $this->em->flush();
            
            $this->addFlash('success', 'Successfully added');
            return $this->redirectToRoute('add_stagiaire');
        }
        
        return $this->render('stagiaire/addstagiaire.html.twig', [
            'form' => $form->createView(),
            'edit' => $stagiaire->getId(),
        ]);
    }

    #[Route('/stagiaire/{id}/delete', name: 'delete_stagiaire')]
    public function deleteStagiaire(Stagiaire $stagiaire)
    {
        $this->em->remove($stagiaire);
        $this->em->flush();

        return $this->redirectToRoute('stagiaire');
    }

    #[Route('/stagiaire/{id}', name: 'show_stagiaire')]
    public function showStagiaire(Stagiaire $stagiaire): Response
    {
        
        return $this->render('stagiaire/showstagiaire.html.twig', [
            'detailstagiaire' => $stagiaire,
        ]);
    }
}
