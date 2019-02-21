<?php

namespace AppBundle\Controller;

use Cooking\MembresBundle\Entity\Membres;
use Cooking\RecettesBundle\Entity\Recettes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use AppBundle\Form\TaskType;
use AppBundle\Form\DecoType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DefaultController extends Controller
{
    
    /**
     * @Route("/", name="homepage")
     */
    public function accueilAction(Request $request)
    {
        return $this->redirectToRoute('index');
    }

    /**
     * @Route("/index", name="index")
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(TaskType::class);
        $deconnexion = $this->createForm(DecoType::class);
        $deconnexion->handleRequest($request); 
        $session = new Session();
        if ($deconnexion->isSubmitted()) {
            $session->clear();
            return $this->redirectToRoute('index');
        }
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $recherche = $form->getData();
            var_dump($recherche);
            $session->set('recherche',$recherche['titre']);
            return $this->redirectToRoute('recherche');
        }
    
        $entityManager = $this->getDoctrine()->getManager();

        $recettes = new Recettes();
        $recettes = $this->getDoctrine()
                        ->getRepository(Recettes::class)
                        ->findAll();

        $membres = new Membres();
        $membres = $this->getDoctrine()
                        ->getRepository(Membres::class)
                        ->findAll();

        return $this->render('@App/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'form' => $form->createView(), 
            'deconnexion' => $deconnexion->createView(), 
            'recettes' => $recettes,
            'membres' => $membres
        ]);
    }

}
