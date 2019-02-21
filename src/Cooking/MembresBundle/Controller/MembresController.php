<?php

namespace Cooking\MembresBundle\Controller;

use Cooking\MembresBundle\Entity\Membres;
use Cooking\RecettesBundle\Entity\Recettes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Form\TaskType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Form\DecoType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Vich\UploaderBundle\Form\Type\VichImageType;


class MembresController extends Controller
{
    /**
     * @Route("/connexion", name="connexion")
     */
    public function connexionAction(Request $request)
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

        $membre = new Membres();

        $form_connexion = $this->createFormBuilder($membre)
            ->add('login', TextType::class, ['label' => 'Login'])
            ->add('password', TextType::class, ['label' => 'Mot de passe'])
            ->add('save', SubmitType::class, ['label' => 'Se connecter'])
            ->getForm();

        $form_connexion->handleRequest($request); 

        $login = $membre->getLogin();
        $passwd = $membre->getPassword(); 

        if ($form_connexion->isSubmitted() && $form_connexion->isValid()) {
            $repository = $this->getDoctrine()->getRepository(Membres::class);
            $membre = $repository->findOneByLogin($login);
            if ($passwd == $membre->getPassword()){
                $session->set('id', $membre->getIdMembre());
                $session->set('login', $login);
                $session->set('password', $passwd);
                $session->set('gravatar', $membre->getImageName());
                $session->set('prenom', $membre->getPrenom());
                $session->set('nom', $membre->getNom());

                $recettes = new Recettes();
                $recettes = $this->getDoctrine()
                                ->getRepository(Recettes::class)
                                ->findAll();
                echo "Connexion effectuée!";

                $membres = new Membres();
                $membres = $this->getDoctrine()
                        ->getRepository(Membres::class)
                        ->findAll();

                return $this->render('@App/index.html.twig', [
                    'form_connexion' => $form_connexion->createView(), 
                    'form' => $form->createView(),
                    'recettes' => $recettes,
                    'membres' => $membres,
                    'deconnexion' => $deconnexion->createView(), 
                ]);

            }
            else{
                echo "Erreur sur le mot de passe ou le login";
            }
        }

        return $this->render('@CookingMembres/connexion.html.twig', [
            'form_connexion' => $form_connexion->createView(), 
            'form' => $form->createView(), 
            'deconnexion' => $deconnexion->createView(), 
        ]);
    }

    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscriptionAction(Request $request)
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
            $session->set('recherche',$recherche['titre']);
            return $this->redirectToRoute('recherche');
        }

        $membre = new Membres();

        $form_inscription = $this->createFormBuilder($membre)
            ->add('prenom', TextType::class, ['label' => 'Prenom'])
            ->add('nom', TextType::class, ['label' => 'Nom'])
            ->add('login', TextType::class, ['label' => 'Login'])
            ->add('password', TextType::class, ['label' => 'Mot de passe'])
            ->add('imageFile', VichImageType::class)
            ->add('save', SubmitType::class, ['label' => 'S\'inscrire'])
            ->getForm();

        $form_inscription->handleRequest($request); 

        if ($form_inscription->isSubmitted() && $form_inscription->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $membre = $form_inscription->getData();
            $entityManager->persist($membre);
            $entityManager->flush();

            return $this->redirectToRoute('connexion');
        }

        return $this->render('@CookingMembres/inscription.html.twig', [
            'form' => $form->createView(), 
            'form_inscription' => $form_inscription->createView(), 
            'deconnexion' => $deconnexion->createView(), 
        ]);
    }

    /**
     * @Route("/compte", name="compte")
     */

    public function compteAction(Request $request)
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
            $session->set('recherche',$recherche['titre']);
            return $this->redirectToRoute('recherche');
        }

        $membre = new Membres();
        
        $form_supp = $this->createFormBuilder($membre)
        ->add('save', SubmitType::class, ['label' => 'Supprimer compte'])
        ->getForm();

        $form_supp->handleRequest($request);
        
        if ($form_supp->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $membre = $this->getDoctrine()
            ->getRepository(Membres::class)
            ->findOneByLogin($session->get('login'));

            $entityManager->remove($membre);
            $entityManager->flush();
            $session->clear();
            return $this->redirectToRoute('index');
        }

       
        $membres = new Membres();
        $membres = $this->getDoctrine()
                        ->getRepository(Membres::class)
                        ->findOneByLogin($session->get('login'));
                        
        $recettes = new Recettes();
        $recettes = $this->getDoctrine()
                        ->getRepository(Recettes::class)
                        ->findByMembre($membres->getIdmembre());  

        return $this->render('@CookingMembres/compte.html.twig', [
            'form' => $form->createView(), 
            'form_supp' => $form_supp->createView(), 
            'deconnexion' => $deconnexion->createView(), 
            'recettes' => $recettes, 
            'membres' => $membres
        ]);
    }

    /**
     * @Route("/compte/mdp", name="mdp")
     */
    public function mdpAction(Request $request)
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
            $session->set('recherche',$recherche['titre']);
            return $this->redirectToRoute('recherche');
        }

        $membre = new Membres();
        
        $form_mdp = $this->createFormBuilder($membre)
        ->add('password', TextType::class, ['label' => 'Mot de passe'])
        ->add('save', SubmitType::class, ['label' => 'Changer'])
        ->getForm();

        $form_mdp->handleRequest($request); 

        if ($form_mdp->isSubmitted() && $form_mdp->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $membre = $form_mdp->getData();
            $mdp = $membre->getPassword();

            $repository = $this->getDoctrine()->getRepository(Membres::class);
            $membre = $repository->findOneByLogin($session->get('login'));
            $membre->setPassword($mdp);
            
            $entityManager->persist($membre);
            $entityManager->flush();
            
            echo "Mot de passe modifié avec succès!";
        }

        return $this->render('@CookingMembres/update_passwd.html.twig', [
            'form' => $form->createView(), 
            'form_mdp' => $form_mdp->createView(), 
            'deconnexion' => $deconnexion->createView(), 
        ]);
    }

     /**
     * @Route("/compte/gravatar", name="gravatar")
     */
    public function gravatarAction(Request $request)
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
            $session->set('recherche',$recherche['titre']);
            return $this->redirectToRoute('recherche');
        }

        $membre = new Membres();
        
        $update = $this->createFormBuilder($membre)
        ->add('imageFile', VichImageType::class)
        ->add('save', SubmitType::class, ['label' => 'Modifier Gravatar'])
        ->getForm();

        $update->handleRequest($request);

        if ($update->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $membre = $update->getData();
            $image=$membre->getImageFile();

            $repository = $this->getDoctrine()->getRepository(Membres::class);
            $membre = $repository->findOneByLogin($session->get('login'));
            $membre->setImageFile($image);
            $entityManager->persist($membre);
            $entityManager->flush();
            
            echo "Gravatar modifié avec succès!";
            $session->set('gravatar', $membre->getImageName());
        }

        return $this->render('@CookingMembres/update_gravatar.html.twig', [
            'form' => $form->createView(), 
            'update' => $update->createView(), 
            'deconnexion' => $deconnexion->createView(), 
        ]);
    }
    
     /**
     * @Route("/membre/{id}", name="membre")
     */

    public function membreAction(Request $request, $id)
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
            $session->set('recherche',$recherche['titre']);
            return $this->redirectToRoute('recherche');
        }

        $entityManager = $this->getDoctrine()->getManager();

        $membres = new Membres();
        $membres = $this->getDoctrine()
                        ->getRepository(Membres::class)
                        ->findOneByLogin($id);

        $recettes = new Recettes();
        $recettes = $this->getDoctrine()
                        ->getRepository(Recettes::class)
                        ->findByMembre($membres->getIdmembre());

        return $this->render('@CookingMembres/membre.html.twig', [
            'form' => $form->createView(), 
            'deconnexion' => $deconnexion->createView(), 
            'id'=>$id,
            'recettes'=> $recettes,
            'membres'=> $membres
        ]);
    }

}
