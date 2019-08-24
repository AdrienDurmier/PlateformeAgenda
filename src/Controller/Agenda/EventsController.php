<?php

namespace App\Controller\Agenda;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Agenda\Events;

class EventsController extends AbstractController
{
    /**
     * @Route("/admin/agenda/events/edit", name="agenda.events.edit")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request)
    {
        $datas = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        $id = $datas['Event'][0];
        $start = $datas['Event'][1];
        $end = $datas['Event'][2];
        $event = $this->getDoctrine()->getRepository(Events::class)->find($id);
        $event->setStart($start);
        $event->setEnd($end);

        $em->persist($event);
        $em->flush();

        return $this->redirectToRoute('agenda.calendar.index');
    }
}