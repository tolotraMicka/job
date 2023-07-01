<?php

namespace App\Controller;

use App\Entity\Particulier;
use App\Entity\Recruteur;
use App\Form\RegistrationRecruteurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SecurityRecruteurController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em=$em;
    }
    /**
     * @Route("/inscription_recruteur", name="app_inscription_recruteur")
     */
    public function registration(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $recruteur = new Recruteur();
        $roles=["ROLE_RECRUTEUR","ROLE_PARTICULIER"];
    
        // changement ato
        $form= $this->createForm(RegistrationRecruteurType::class,$recruteur);
        // analyser la requete dans l'inscription
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            
            $hash= $encoder->encodePassword($recruteur, $recruteur->getPassword());
            $recruteur->setPassword($hash);
            $type=$request->request->get("registration_recruteur")["type"];
            // dd($request,$type);
            $type =="recruteur" ? $recruteur->setRoles([$roles[0]]) : $recruteur->setRoles([$roles[1]]);  

            $this->em->persist($recruteur);
            $this->em->flush();

            return $this->redirectToRoute('app_connexion_recruteur');
        }
        return $this->render('security_recruteur/registrationRecruteur.html.twig', [
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/connexion_recruteur", name="app_connexion_recruteur")
     */
    public function login(Request $request, UserPasswordEncoderInterface $passwordEncoder,TokenStorageInterface $tokenStorage, AuthenticationUtils $authenticationUtils): Response
    {
          // dd($request,$user);
            // Vérifier si l'utilisateur existe et si le mot de passe est valide
            if ($request->isMethod('POST')) {
                $email = $request->request->get('_email');
                $password = $request->request->get('_password');
       
        // Récupérer l'utilisateur correspondant à l'email
                $recruteur = $this->getDoctrine()->getRepository(Recruteur::class)->findOneBy(['email' => $email]);
                // dd($user);
            if ($recruteur && $passwordEncoder->isPasswordValid($recruteur, $password)) {
                $token = new UsernamePasswordToken($recruteur, null, "main",$recruteur->getRoles());
                $tokenStorage->setToken($token);
                
                // Rediriger l'utilisateur vers la page d'accueil ou toute autre page souhaitée
                //vérifier si recruteur ou particulier
                $sql = "SELECT type FROM recruteur
                            WHERE email = '".$request->request->get('_email')."'";

                $connection = $this->em->getConnection();
                $statement = $connection->executeQuery($sql);
                $recruteur = $statement->fetchAllAssociative();

                if($recruteur[0]['type'] == 'recruteur') {
                    return $this->redirectToRoute('app_home_recruteur');
                }
                else if($recruteur[0]['type'] == 'particulier') {
                    return $this->redirectToRoute('app_home_particulier');
                }


            } else {
                // Afficher un message d'erreur
                $this->addFlash('error', 'email ou mot de passe incorrect');
                // Rediriger l'utilisateur vers la page de connexion
                return $this->redirectToRoute('app_connexion_recruteur');
            }
        // }else{
        //     $this->addFlash('error', 'Invalid email or password');
        //     // Rediriger l'utilisateur vers la page de connexion
        //     return $this->redirectToRoute('app_connexion_user');
        // }
            }

        return $this->render('security_recruteur/loginRecruteur.html.twig', [
           
        ]);
    }

    /**
     * @Route("/page_interdite", name="page_interdite")
     */
    public function pageInterdit() :Response{
        return $this->render("page_interdit/alert_page_interdit.html.twig",[]);
    }


     /**
     * @Route("/deconnexion", name="app_deconnexion")
     */
    public function logout()
    {
       
    }
}
