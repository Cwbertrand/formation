<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Entity\InstituleSession;
use App\Form\InstituleSessionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class InstituleSessionController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/institulesession', name: 'instituleSession')]
    public function index(): Response
    {
        $form = $this->em->getRepository(InstituleSession::class)->findBy([], ['themesession' => 'ASC']);
        
        return $this->render('institule_session/index.html.twig', [
            'forms' => $form,
        ]);
    }

    //add and edit session
    #[Route('/institulesession/add', name: 'add_instituleSession')]
    #[Route('/institulesession/{id}/edit', name: 'edit_instituleSession')]
    public function addSession(InstituleSession $instituleSession = null, Request $request)
    {
        if(!$instituleSession){
            $instituleSession = new InstituleSession();
        }

        $form = $this->createForm(InstituleSessionType::class, $instituleSession);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $instituleSession = $form->getData();
            $this->em->persist($instituleSession);
            $this->em->flush();

            return $this->redirectToRoute('add_instituleSession');
        }
        
        return $this->render('institule_session/addsession.html.twig', [
            'form' => $form->createView(),
            'edit' => $instituleSession->getId(),
        ]);
    }

    #[Route('/institulesession/{id}/delete', name: 'delete_instituleSession')]
    public function deleteSession(InstituleSession $instituleSession): Response
    {
        $this->em->remove($instituleSession);
        $this->em->flush();
        return $this->redirectToRoute('instituleSession');
    }

    #[Route('/institulesession/{id}', name: 'show_instituleSession')]
    public function showSession(InstituleSession $instituleSession): Response
    {
        $noninscrit = $this->em->getRepository(Stagiaire::class)->findNonInscrit($instituleSession->getId());

        return $this->render('institule_session/showsession.html.twig', [
            'sessiondetails' => $instituleSession,
            'noninscrits' => $noninscrit,
        ]);
    }


    /**
     * doctrineconverter options
     * The ParamConverter annotation calls converters to convert request parameters to objects. 
     * These objects are stored as request attributes and so they can be injected as controller
     * mapping: Configures the properties and values to use with the findOneBy() method: 
     * the key is the route placeholder name and the value is the Doctrine property name:
    */

    //Inscrit un stagiaire
    #[ParamConverter('institulesession', options: ['mapping' => ['dateidinstitulesession' => 'id']])]
    #[ParamConverter('stagiaire', options: ['mapping' => ['idstagiaire' => 'id']])]
    #[Route('/institulesession/inscrire/{idinstitulesession}/{idstagiaire}', name: 'inscrire_stagiaire')]
    public function inscrireStagiaire(InstituleSession $institulesession, Stagiaire $stagiaire): Response
    {
        if($institulesession->getPlaceRestant() > 0){
            $institulesession->addStagiaire($stagiaire);
            
            $this->em->flush();
        }
        
        $noninscrit = $this->em->getRepository(Stagiaire::class)->findNonInscrit($institulesession->getId());
        return $this->render('institule_session/showsession.html.twig', [
            'sessiondetails' => $institulesession,
            'noninscrits' => $noninscrit,
        ]);
    }


    //desinscrit un stagiaire
    #[ParamConverter('institulesession', options: ['mapping' => ['dateidinstitulesession' => 'id']])]
    #[ParamConverter('stagiaire', options: ['mapping' => ['idstagiaire' => 'id']])]
    #[Route('/institulesession/desinscrire/{idinstitulesession}/{idstagiaire}', name: 'desinscrire_stagiaire')]
    public function desinscrireStagiaire(InstituleSession $institulesession, Stagiaire $stagiaire): Response
    {
        $institulesession->removeStagiaire($stagiaire);
        $this->em->flush();
        $noninscrit = $this->em->getRepository(Stagiaire::class)->findNonInscrit($institulesession->getId());
        return $this->render('institule_session/showsession.html.twig', [
            'sessiondetails' => $institulesession,
            'noninscrits' => $noninscrit,
        ]);
    }

}
