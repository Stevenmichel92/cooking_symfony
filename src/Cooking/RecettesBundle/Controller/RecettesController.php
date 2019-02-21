<?php

namespace Cooking\RecettesBundle\Controller;

use Cooking\MembresBundle\Entity\Membres;
use Cooking\RecettesBundle\Entity\Recettes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use AppBundle\Form\TaskType;
use AppBundle\Form\DecoType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Vich\UploaderBundle\Form\Type\VichImageType;



class RecettesController extends Controller
{
    /**
     * @Route("/recettes", name="recettes")
     */
    public function recettesAction(Request $request)
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

        $membres = $this->getDoctrine()
                        ->getRepository(Membres::class)
                        ->findAll();
        
        return $this->render('@CookingRecettes/recettes.html.twig', [
            'recettes' => $recettes,
            'form' => $form->createView(),
            'deconnexion' => $deconnexion->createView(),  
            'membres'=> $membres
        ]);
    }

    /**
     * @Route("/recherche", name="recherche")
     */
    public function rechercheAction(Request $request)
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
   
        $recettes = $this->getDoctrine()
                        ->getRepository(Recettes::class)
                        ->findByTitre($session->get('recherche'));

        $membres = $this->getDoctrine()
                        ->getRepository(Membres::class)
                        ->findAll();

        return $this->render('@CookingRecettes/recherche.html.twig', [
            'recettes' => $recettes,
            'form' => $form->createView(), 
            'deconnexion' => $deconnexion->createView(), 
            'membres'=> $membres
        ]);
    }

    /**
     * @Route("/depot_recette", name="depot_recette")
     */
    public function depotAction(Request $request)
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

        $recette = new Recettes();
 
        $depot = $this->createFormBuilder($recette)
        ->add('titre', TextType::class, ['label' => 'Titre'])
        ->add('chapo', TextType::class, ['label' => 'Commentaire'])
        ->add('preparation', TextType::class, ['label' => 'Preparation'])
        ->add('ingredient', TextType::class, ['label' => 'Ingredients'])
        ->add('couleur', TextType::class, ['label' => 'Couleur'])
        ->add('categorie', TextType::class, ['label' => 'Categorie'])
        ->add('tempsCuisson', TextType::class, ['label' => 'Temps Cuisson'])
        ->add('tempsPreparation', TextType::class, ['label' => 'Temps Preparation'])
        ->add('difficulte', TextType::class, ['label' => 'Difficultés'])
        ->add('prix', TextType::class, ['label' => 'Prix'])
        ->add('imageFile', VichImageType::class)
        ->add('save', SubmitType::class, ['label' => 'Envoyer ma recette'])
        ->getForm();

        $depot->handleRequest($request); 

        if ($depot->isSubmitted() && $depot->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $recette = $depot->getData();
            $recette->setMembre($session->get('id'));
            $entityManager->persist($recette);
            $entityManager->flush();

            echo "Recette ajoutée";
        }

        return $this->render('@CookingRecettes/depot_recette.html.twig', [
            'form' => $form->createView(), 
            'depot' => $depot->createView(), 
            'deconnexion' => $deconnexion->createView(), 
        ]);
    }

}


