<?php

namespace App\Controller;


use App\Entity\ToDoList;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     */
    public function index()
    {
        return $this->render('back/default/index.html.twig');
    }
    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $request->request->all();
        $user = (new User())
            ->setFirstname($data['firstname'])
            ->setLastname($data['lastname'])
            ->setEmail($data['email'])
            ->setRoles(['ROLE_USER'])
            ->setBirthday(new \DateTime($data['birthday']))
            ->setPassword($data['password']);
        $em->persist($user);
        $em->flush();

        if($user->isValid()){
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
            $em->persist($user);
            $liste = new ToDoList();
            $liste->setAuteur($user);
            $em->persist($liste);
            $em->flush();
            $this->addFlash('notice','Compte bien crée');
            return $this->redirectToRoute('app_login');
        }
        else{
            $em->remove($user);
            $em->flush();
            $this->addFlash('error','User non valide');
            return $this->redirectToRoute('app_register');
        }
    }
}