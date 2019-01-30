<?php

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityControllerController extends Controller
{
    /**
     * @Route("/inscription")
     */
    public function registerAction(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder)
    {
        $user=new User();
        $form=$this->createForm(UserType::Class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $manager->persist($user);
            $manager->flush();
            return $this ->redirectToRoute('login');
        }
        return $this->render('@App/SecurityController/register.html.twig', array(
            'form'=>$form->createView()
        ));
    }

    /**
     * @Route("/connexion", name="login")
     */
    public function loginAction()
    {
        return $this->render('@App/SecurityController/login.html.twig', array(
            //
        ));
    }

    /**
     * @Route("/logout")
     */
    public function logoutAction(){}

}
