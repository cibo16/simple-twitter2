<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\Message;
use AppBundle\Form\MessageType;
use AppBundle\Repository\MessageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class MessageController extends Controller
{
    /**
     * @Route("/messages/showMessage", name="messages")
     */
    public function showMessage()
    {
        $repo=$this->getDoctrine()->getRepository(Message::class);
        $messages=$repo->findAll();

        return $this->render('@App/Messages/messages.html.twig', array(
            'messages'=>$messages
        ));
    }

    /**
     * @Route("/messages/postMessage", name="post_message")
     */
    public function postMessage(Request $request)
    {
        $message= new Message();
        $form=$this->createForm(MessageType::Class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $message->setCreatedAt(new \DateTime());
            $message->setUser($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();
            return $this ->redirectToRoute('messages');
        }
        return $this->render('@App/Messages/sendMessage.html.twig', array(
            'form'=>$form->createView()
        ));
    }
    /**
     * @Route("/messages/deleteMessage/{id}", name="supprimer_message")
     */
    public function deleteMessage(Request $request,$id)
    {
        $repo=$this->getDoctrine()->getRepository(Message::class);
        $message=$repo->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($message);
        $em->flush();
        return $this ->redirectToRoute('messages');
    }
    /**
     * @Route("/messages/likeMessage/{id}", name="like_message")
     */
    public function likeMessage(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $repo=$this->getDoctrine()->getRepository(Message::class);
        $message=$repo->find($id);
        $username = $this->getUser()->getusername();
        // if ($message->getLikes()==null) {
        //     $likes = explode(" ", ($this->getUser()->getusername()));
        // } else {
            $likes=$message->getLikes();
            //$likes=array_push($likes1,($this->getUser()->getusername()));
            //}
            var_dump($likes);
        if ($likes == null){
            $likes = array();
            array_push($likes, $username);
    }
        else if (!in_array($username,$likes)) {
            array_push($likes, $username);
        }

        $message->setLikes($likes);
        $em->flush();
        return $this ->redirectToRoute('messages');
    }
}
