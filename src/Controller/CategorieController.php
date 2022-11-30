<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategorieController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    #[Route('/categorie', name: 'categorie')]
    public function index(): Response
    {
        $form = $this->em->getRepository(Categorie::class)->findBy([], ['nomcategorie' => 'ASC']);
        return $this->render('categorie/index.html.twig', [
            'forms' => $form,
        ]);
    }

    //edit et add categorie
    #[Route('/categorie/add', name: 'add_categorie')]
    #[Route('/categorie/{id}/edit', name: 'edit_categorie')]
    public function addCategorie(Categorie $categorie = null, Request $request)
    {
        if(!$categorie){
            $categorie = new Categorie();
        }

        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $categorie = $form->getData();
            $this->em->persist($categorie);
            $this->em->flush();

            return $this->redirectToRoute('add_categorie');
        }
        
        return $this->render('categorie/addcategorie.html.twig', [
                    'form' => $form->createView(),
                    'edit' => $categorie->getId(),
        ]);
    }

    #[Route('/categorie/{id}/delete', name: 'delete_categorie')]
    public function deleteSession(Categorie $categorie): Response
    {
        $this->em->remove($categorie);
        $this->em->flush();
        return $this->redirectToRoute('categorie');
    }

    #[Route('/categorie/{id}', name: 'show_categorie')]
    public function showSession(Categorie $categorie): Response
    {
        return $this->render('categorie/showcategorie.html.twig', [
            'detaileCategorie' => $categorie,
        ]);
    }



}
