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
     */
    public function add(Request $request)
    {
        $datas = $request->request->all();
        $em = $this->getDoctrine()->getManager();

        $event = new Event();
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
        $event = $this->getDoctrine()->getRepository(Event::class)->find($id);
        $event->setStart($start);
        $event->setEnd($end);

        $em->persist($event);
        $em->flush();

        return $this->redirectToRoute('agenda.calendar.index');
    }

    // Date Utilities
    //----------------------------------------------------------------------------------------------

    // Parses a string into a DateTime object, optionally forced into the given timeZone.
    function parseDateTime($string, $timeZone=null) {
        $date = new DateTime(
            $string,
            $timeZone ? $timeZone : new DateTimeZone('UTC')
        // Used only when the string is ambiguous.
        // Ignored if string has a timeZone offset in it.
        );
        if ($timeZone) {
            // If our timeZone was ignored above, force it.
            $date->setTimezone($timeZone);
        }
        return $date;
    }

    // Takes the year/month/date values of the given DateTime and converts them to a new DateTime,
    // but in UTC.
    function stripTime($datetime) {
        return new DateTime($datetime->format('Y-m-d'));
    }
}