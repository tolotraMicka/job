<?php

namespace App\Controller;

use App\Entity\Candidats;
use App\Entity\User;
use App\Form\RegistrationUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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
    public function registration(Request $request,SessionInterface $session, UserPasswordEncoderInterface $encoder): Response
    {
        // $session=$session->get("value");
        // dd($request,$session);
        $user = new User();
        $user_candidat= new Candidats();
        $roles=["ROLE_CANDIDAT","ROLE_JOBBEUR"];
        $form= $this->createForm(RegistrationUserType::class,$user);
        // analyser la requete dans l'inscription
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $hash= $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
        
            $type=$request->request->get("registration_user")["type"];
            // dd($request,$type);
            if($type =="candidat"){
                $user->setRoles([$roles[0]]);
            }
            if($type =="jobbeur"){
                $user->setRoles([$roles[1]]);
            } 
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
    public function login(Request $request,SessionInterface $session, UserPasswordEncoderInterface $passwordEncoder, TokenStorageInterface $tokenStorage, AuthenticationUtils $authenticationUtils): Response
    {
            // Vérifier si l'utilisateur existe et si le mot de passe est valide
            if ($request->isMethod('POST')) {
                $email = $request->request->get('_email');
                $password = $request->request->get('_password');
                
                $id_session_offre=$session->get("value");
                // Récupérer l'utilisateur correspondant à l'email
                $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email' => $email]);
                // dd($user);
            if ($user && $passwordEncoder->isPasswordValid($user, $password)) {
                if($id_session_offre !=null){
                    $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                    $tokenStorage->setToken($token);
                    // Rediriger l'utilisateur vers la page d'accueil ou toute autre page souhaitée
                    $session->remove('value');
                    return $this->redirectToRoute('postule_offre',['id'=>$id_session_offre]);
                }else{
                    $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                    $tokenStorage->setToken($token);
                    // Rediriger l'utilisateur vers la page d'accueil ou toute autre page souhaitée
                    return $this->redirectToRoute('app_home');
                }
               
            }else {
                // Afficher un message d'erreur
                $this->addFlash('error', 'email ou mot de passe incorrect');
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
