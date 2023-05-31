<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
    public function login(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
       
        return $this->render('security_user/loginUser.html.twig', [
            
        ]);
    }

     /**
     * @Route("/deconnexion_user", name="app_deconnexion_user")
     */
    public function logout()
    {
       
    }
}
