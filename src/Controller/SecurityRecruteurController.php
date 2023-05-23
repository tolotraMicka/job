<?php

namespace App\Controller;

use App\Entity\Recruteur;
use App\Form\RegistrationRecruteurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SecurityRecruteurController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em=$em;
    }
    
    /**
     * @Route("/register_recruteur", name="app_registerRecruteur")
     */
    public function registration(Request $request,UserPasswordEncoderInterface $encoder): Response
    {
         $recruteur = new Recruteur();
         $form = $this->createForm(RegistrationRecruteurType::class,$recruteur);

         $form->handleRequest($request);

         if($form->isSubmitted()&& $form->isvalid()){
            $hash=$encoder->encodePassword($recruteur,$recruteur->getPassword());
           
            $recruteur->setPassword($hash);
            // $recruteur->setRoles(["USER_RECRUTEUR"]);
            $this->em->persist($recruteur);
            $this->em->flush();

            return $this->redirectToRoute('app_loginRecruteur');
         }
        return $this->render('security_recruteur/registration.html.twig', [
             'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/login_recruteur",name="app_loginRecruteur", methods={"GET", "POST"})
     */
    public function login(Request $request, UserPasswordEncoderInterface $passwordEncoder, TokenStorageInterface $tokenStorage): Response
    {
        // Vérifier si l'utilisateur est déjà authentifié
        // dd($request);
        // if ($tokenStorage->getToken() !== null && $this->isGranted('USER_RECRUTEUR')) {
        //     // Rediriger l'utilisateur vers une autre page s'il est déjà authentifié
        //     return $this->redirectToRoute('app_home');
        // }
        $userconnect= $request->attributes->get("_firewall_context");
        // "security.firewall.map.context.main"
// dd($request);
        // Vérifier les données du formulaire de connexion
        if ($request->isMethod('POST')&& $this->isGranted('USER_RECRUTEUR')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');

            // Récupérer l'utilisateur correspondant à l'email
            $recruteur = $this->getDoctrine()->getRepository(Recruteur::class)->findOneBy(['email' => $email]);
            
            // Vérifier si l'utilisateur existe et si le mot de passe est valide
            if ($recruteur && $passwordEncoder->isPasswordValid($recruteur, $password)) {
                // Connecter l'utilisateur manuellement
                $token = new UsernamePasswordToken($recruteur, null, 'recruteur', $recruteur->getRoles());
                $tokenStorage->setToken($token,'recruteur');

                // Rediriger l'utilisateur vers la page d'accueil ou toute autre page souhaitée
                return $this->redirectToRoute('app_home');
            }
            //  else {
            //     throw new BadCredentialsException('Invalid email or password');
            // }
        }

        // Afficher le formulaire de connexion
        return $this->render('security_recruteur/login.html.twig');
    }

    /**
     * @Route("/logout", name="app_logoutRecruteur")
     */
    public function logout(): void
    {
        // Cette méthode ne sera jamais exécutée car la déconnexion est gérée par Symfony
        throw new \LogicException('This method should not be called.');
    }

}

  
