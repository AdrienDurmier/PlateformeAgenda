<?php

namespace App\Controller\Agenda;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Agenda\Event;

class EventController extends AbstractController
{
    /**
     * @Route("/admin/agenda/events/load", name="agenda.events.load")
     * @param Request $request
     * @return Response
     */
    public function load(Request $request)
    {
        $datas = $request->request->all();
        $events = $this->getDoctrine()->getRepository(Event::class)->findAll();

        $response = [];

        foreach($events as $event){
            $response[] = [
                'id' => $event->getId(),
                'title' => $event->getTitle(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
            ];
        }
        return new JsonResponse($response);
    }

    /**
     * @Route("/admin/agenda/events/add", name="agenda.events.add")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function add(Request $request)
    {
        $datas = $request->request->all();
        $start = new \DateTime($datas['start']);
        $start->setTimeZone(new \DateTimeZone('UTC'));
        $end = new \DateTime($datas['end']);
        $end->setTimeZone(new \DateTimeZone('UTC'));
        $em = $this->getDoctrine()->getManager();

        $event = new Event();
        $event->setTitle($datas['title']);
        $event->setStart($start);
        $event->setEnd($end);
        $em->persist($event);
        $em->flush();

        $response = [
            'event' => $event,
        ];

        return new JsonResponse($response);
    }

    /**
     * @Route("/admin/agenda/events/resize", name="agenda.events.resize")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function resize(Request $request)
    {
        $datas = $request->request->all();
        $id = $datas['id'];
        $start = new \DateTime($datas['start']);
        $start->setTimeZone(new \DateTimeZone('UTC'));
        $end = new \DateTime($datas['end']);
        $end->setTimeZone(new \DateTimeZone('UTC'));

        $em = $this->getDoctrine()->getManager();

        $event = $this->getDoctrine()->getRepository(Event::class)->find($id);
        $event->setStart($start);
        $event->setEnd($end);
        $em->persist($event);
        $em->flush();

        $response = [
            'event' => $event,
        ];

        return new JsonResponse($response);
    }

}