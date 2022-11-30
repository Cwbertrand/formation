<?php

namespace App\Controller;

use App\Entity\Formateur;
use App\Form\FormateurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormateurController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/formateur', name: 'formateur')]
    public function index(): Response
    {
        $formateur = $this->em->getRepository(Formateur::class)->findBy([], ['nom' => 'ASC']);
        return $this->render('formateur/index.html.twig', [
            'forms' => $formateur,
        ]);
    }
    
    //edit et add formateur
    #[Route('/formateur/add', name: 'add_formateur')]
    #[Route('/formateur/{id}/edit', name: 'edit_formateur')]
    public function addFormateur(Request $request, Formateur $formateur = null)
    {
        if(!$formateur){
            $formateur = new Formateur();
        }

        $form = $this->createForm(FormateurType::class, $formateur);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $formateur = $form->getData();
            $this->em->persist($formateur);
            $this->em->flush();
            
            $this->redirectToRoute('add_formateur');
        }
        return $this->render('formateur/addformateur.html.twig', [
            'form' => $form->createView(),
            'edit' => $formateur->getId(),
        ]);
    }
}
