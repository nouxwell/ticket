<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Form\TicketType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {
        $data = $this->getDoctrine()->getRepository(Ticket::class)->findAll();
        return $this->render('main/index.html.twig', [
            'list' => $data,
        ]);
    }

    /**
     * @Route("create", name="create")
     */
    public function create(Request $request){
        $ticket = new Ticket();
        $form = $this->createForm(TicketType::class,$ticket);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($ticket);
            $em->flush();
            $this->addFlash('notice','Submitted Successfully!');
            return $this->redirectToRoute('main');
        }
        return $this->render('main/create.html.twig',[
            'form' => $form->createView()
        ]);

    }

    /**
     * @Route("/update/{id}", name="update")
     */
    public function update(Request $request, $id){
        $crud = $this->getDoctrine()->getRepository(Ticket::class)->find($id);
        $form = $this->createForm(TicketType::class, $crud);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($crud);
            $em->flush();
            $this->addFlash('notice','Updated Successfully!');
            return $this->redirectToRoute('main');
        }
        return $this->render('main/update.html.twig',[
            'form' => $form->createView()
        ]);
    }



    /**
     * @Route("/show/{id}", name="show")
     */
    public function show($id){
        $ticket = $this->getDoctrine()->getRepository(Ticket::class)->find($id);
        return $this->render('main/show.html.twig', [
            'ticket' => $ticket
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete($id){
        $data = $this->getDoctrine()->getRepository(Ticket::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($data);
        $em->flush();
        $this->addFlash('notice','Deleted Successfully!');
        return $this->redirectToRoute('main');
    }





}
