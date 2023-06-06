<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SecurityUserController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em){
        $this->em=$em;
    }
    /**
     * @Route("/inscription_user", name="app_inscription_user")
     */
    public function registration(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
         // changement ato
        $form= $this->createForm(RegistrationUserType::class,$user);
        // analyser la requete dans l'inscription
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $hash= $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $this->em->persist($user);
            $this->em->flush();

            return $this->redirectToRoute('app_connexion_user');
        }
        return $this->render('security_user/registrationUser.html.twig', [
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/connexion_user", name="app_connexion_user")
     */
    public function login(Request $request, UserPasswordEncoderInterface $passwordEncoder, TokenStorageInterface $tokenStorage, AuthenticationUtils $authenticationUtils): Response
    {
        // dd($request,$user);
            // Vérifier si l'utilisateur existe et si le mot de passe est valide
            if ($request->isMethod('POST')) {
                $email = $request->request->get('_email');
                $password = $request->request->get('_password');
       
        // Récupérer l'utilisateur correspondant à l'email
                $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $email]);
                // dd($user);
            if ($user && $passwordEncoder->isPasswordValid($user, $password)) {
                $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                $tokenStorage->setToken($token);
                // Rediriger l'utilisateur vers la page d'accueil ou toute autre page souhaitée
                return $this->redirectToRoute('app_home');
            }else {
                // Afficher un message d'erreur
                $this->addFlash('error', 'email or mot de passe incorrect');
                // Rediriger l'utilisateur vers la page de connexion
                return $this->redirectToRoute('app_connexion_user');
            }
        
            }

            return $this->render('security_user/loginUser.html.twig', [
        ]);
    }

     /**
     * @Route("/deconnexion", name="app_deconnexion")
     */
    public function logout()
    {
       
    }
}
