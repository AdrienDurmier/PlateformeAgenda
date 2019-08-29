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
        $datas = $request->query->all();

        $filtres = [
            //'date_debut' => '2019-08-26 00:00:00',
            //'date_fin' => '2019-08-29 23:59:59'
        ];
        $events = $this->getDoctrine()->getRepository(Event::class)->search($filtres, $datas['username']);

        $response = [];

        foreach($events as $event){
            $response[] = [
                'id' => $event->getId(),
                'title' => $event->getTitle(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'allDay' => $event->getAllDay(),
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
        $event->setAllDay($datas['allDay']);
        $event->setAuthor($this->getUser());
        $event->setUsers($datas['event-add-users']);
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
        $em = $this->getDoctrine()->getManager();
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
        $em->persist($event);
        $em->flush();
        $response = [
            'event' => $event,
        ];
        return new JsonResponse($response);
    }

    /**
     * @Route("/admin/agenda/events/drop", name="agenda.events.drop")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function drop(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
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
        $em->persist($event);
        $em->flush();
        $response = [
            'event' => $event,
        ];
        return new JsonResponse($response);
    }

    /**
     * @Route("/admin/agenda/events/edit", name="agenda.events.edit")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function edit(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $datas = $request->request->all();
        $id = $datas['event-edit-id'];
        $event = $this->getDoctrine()->getRepository(Event::class)->find($id);
        if(isset($datas['event-edit-title'])) {
            $event->setTitle($datas['event-edit-title']);
        }
        if(isset($datas['event-edit-start'])) {
            $start = new \DateTime($datas['event-edit-start']);
            $start->setTimeZone(new \DateTimeZone('UTC'));
            $event->setStart($start);
        }
        if(isset($datas['event-edit-end'])){
            $end = new \DateTime($datas['event-edit-end']);
            $end->setTimeZone(new \DateTimeZone('UTC'));
            $event->setEnd($end);
        }
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
        $em = $this->getDoctrine()->getManager();
        $datas = $request->request->all();
        $id = $datas['event-edit-id'];
        $event = $this->getDoctrine()->getRepository(Event::class)->find($id);
        $em->remove($event);
        $em->flush();
        $response = [
            'event' => $event,
        ];
        return new JsonResponse($response);
    }

}