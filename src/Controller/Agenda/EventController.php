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
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
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
     * @Route("/admin/agenda/events/update", name="agenda.events.update")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function update(Request $request)
    {
        $datas = $request->request->all();
        $id = $datas['id'];
        $event = $this->getDoctrine()->getRepository(Event::class)->find($id);

        if(isset($datas['title'])) {
            $event->setTitle($datas['title']);
        }
        if(isset($datas['start'])) {
            $start = new \DateTime($datas['start']);
            $start->setTimeZone(new \DateTimeZone('UTC'));
            $event->setStart($start);
        }
        if(isset($datas['end'])){
            $end = new \DateTime($datas['end']);
            $end->setTimeZone(new \DateTimeZone('UTC'));
            $event->setEnd($end);
        }

        $em = $this->getDoctrine()->getManager();

        $em->persist($event);
        $em->flush();

        $response = [
            'event' => $event,
        ];

        return new JsonResponse($response);
    }

    /**
     * @Route("/admin/agenda/events/delete", name="agenda.events.delete")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function delete(Request $request)
    {
        $datas = $request->request->all();
        $id = $datas['id'];

        $em = $this->getDoctrine()->getManager();

        $event = $this->getDoctrine()->getRepository(Event::class)->find($id);
        $em->remove($event);
        $em->flush();

        $response = [
            'event' => $event,
        ];

        return new JsonResponse($response);
    }

}