<?php

namespace App\Controller\Agenda;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Agenda\Ticket;

class TicketController extends AbstractController
{
    /**
     * @Route("/admin/agenda/tickets", name="agenda.ticket.index")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $tickets = $this->getDoctrine()->getRepository(Ticket::class)->findAll();

        return $this->render('agenda/ticket/index.html.twig', [
            'tickets' => $tickets,
        ]);
    }

    /**
     * @Route("/admin/agenda/ticket/new", name="agenda.ticket.new", methods="GET|POST")
     * @param Request $request
     * @return RedirectResponse
     */
    public function new(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if ($request->isMethod('POST')) {
            $datas = $request->request->all();
            $ticket = new Ticket();
            $ticket->setTitle($datas['title']);
            $ticket->setContent($datas['content']);
            $ticket->setAuthor($this->getUser());
            $em->persist($ticket);
            $em->flush();
            $this->addFlash('success', "Ticket créé avec succès");
            return $this->redirectToRoute('agenda.ticket.index');
        }
        return $this->render('agenda/ticket/new.html.twig', [
        ]);
    }

    /**
     * @Route("/admin/agenda/ticket/edit/{id}", name="agenda.ticket.edit", methods="GET|POST")
     * @param Ticket $ticket
     * @param Request $request
     * @return RedirectResponse
     */
    public function edit(Ticket $ticket, Request $request)
    {
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $datas = $request->request->all();
            $ticket->setTitle($datas['title']);
            $ticket->setContent($datas['content']);
            $ticket->setAuthor($this->getUser());
            $em->persist($ticket);
            $em->flush();
            $this->addFlash('success', "Ticket modifiée avec succès");
            return $this->redirectToRoute('agenda.ticket.index');
        }
        return $this->render('agenda/ticket/edit.html.twig', [
            'ticket' => $ticket
        ]);
    }

    /**
     * @Route("/admin/agenda/ticket/delete", name="agenda.ticket.delete", methods="POST")
     * @param Request $request
     * @return JsonResponse
     */
    public function ajaxDelete(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $datas = $request->request->all();

        $ticket = $this->getDoctrine()->getRepository(Ticket::class)->find($datas['ticket']);
        $em->remove($ticket);
        $em->flush();

        $response = array(
            'success' => true,
        );
        return new JsonResponse($response);
    }
}