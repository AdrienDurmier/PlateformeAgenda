<?php

namespace App\Controller\Agenda;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Agenda\Events;

class EventsController extends AbstractController
{
    /**
     * @Route("/admin/agenda/events/load", name="agenda.events.load")
     * @param Request $request
     * @return Response
     */
    public function load(Request $request)
    {
        $events = $this->getDoctrine()->getRepository(Events::class)->findAll();
        $data = array();
        foreach($events as $event)
        {
            $data[] = array(
                'id'   => $event["id"],
                'title'   => $event["title"],
                'start'   => $event["start_event"],
                'end'   => $event["end_event"]
            );
        }
        return new JsonResponse($data);
    }

    /**
     * @Route("/admin/agenda/events/add", name="agenda.events.add")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request)
    {
        $datas = $request->request->all();
        $em = $this->getDoctrine()->getManager();

        $event = new Events();
        $event->setTitle(['title']);
        $event->setDescription(['description']);
        $event->setStart($datas['start']);
        $event->setEnd($datas['end']);
        $event->setColor($datas['color']);
        $em->persist($event);
        $em->flush();

        return $this->redirectToRoute('agenda.calendar.index');
    }

    /**
     * @Route("/admin/agenda/events/edit", name="agenda.events.edit")
     * @param Request $request
     * @return Response
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