<?php

namespace App\Controller\Agenda;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Agenda\Event;
use App\Service\APIJoursFeries;

class EventController extends AbstractController
{
    /**
     * @Route("/admin/agenda/events/load", name="agenda.events.load")
     * @param APIJoursFeries $apiJoursFeries
     * @param Request $request
     * @return Response
     */
    public function load(APIJoursFeries $apiJoursFeries, Request $request)
    {
        $datas = $request->query->all();

        $filtres = [
            //'date_debut' => '2019-08-26 00:00:00',
            //'date_fin' => '2019-08-29 23:59:59'
        ];

        // Événements
        $events = $this->getDoctrine()->getRepository(Event::class)->search($filtres, $datas['username']);

        $response = [];

        foreach($events as $event){
            $response[] = [
                'id' => $event->getId(),
                'title' => $event->getTitle(),
                'start' => $event->getStart()->format('Y-m-d H:i:s'),
                'end' => $event->getEnd()->format('Y-m-d H:i:s'),
                'backgroundColor' => $event->getBackgroundColor(),
                'borderColor' => $event->getBorderColor(),
                'textColor' => $event->getTextColor(),
                'allDay' => $event->getAllDay(),
            ];
        }

        // Jours fériés
        $jours_feries = $apiJoursFeries->interroger(date('Y'));
        foreach($jours_feries as $jour_ferie){
            $response[] = [
                'id' => null,
                'title' => 'Férié: '.$jour_ferie->nom_jour_ferie,
                'start' => $jour_ferie->date . ' 00:00:00',
                'end' => $jour_ferie->date . ' 23:59:59',
                'backgroundColor' => '#123456',
                'borderColor' => '#123456',
                'textColor' => '#ffffff',
                'allDay' => true,
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
        $event->setAuthor($this->getUser());
        $event->setBackgroundColor('#375a7f');
        $event->setBorderColor('#375a7f');
        $event->setTextColor('#fff');
        $event->setAllDay(false);
        $event->setUsers($datas['event-add-users']);
        if($datas['allDay'] == 'true'){
            $event->setAllDay(true);
        }
        // Si l'événement n'est pas superposable
        if(!isset($datas['form-event-add-distinct'])){
            // Recherche d'événements déjà existant à cette période
            $filtres = [
                'date_debut' => $event->getStart()->format('Y-m-d H:i:s'),
                'date_fin' => $event->getEnd()->format('Y-m-d H:i:s')
            ];
            $events_superpose_before = $this->getDoctrine()->getRepository(Event::class)->searchSuperposeBefore($filtres, $datas['event-add-users'][0]);
            foreach ($events_superpose_before as $event_superpose_before){
                $event_superpose_before->setStart($end);
                $em->persist($event_superpose_before);
            }
            $events_superpose_after = $this->getDoctrine()->getRepository(Event::class)->searchSuperposeAfter($filtres, $datas['event-add-users'][0]);
            foreach ($events_superpose_after as $event_superpose_after){
                $event_superpose_after->setEnd($start);
                $em->persist($event_superpose_after);
            }
            $events_superpose_over = $this->getDoctrine()->getRepository(Event::class)->searchSuperposeOver($filtres, $datas['event-add-users'][0]);
            foreach ($events_superpose_over as $event_superpose_over){
                // clone de l'évenement pour la partie après
                $event_superpose_over2 = clone $event_superpose_over;
                $event_superpose_over2->setStart($end);
                $event_superpose_over2->setEnd($event_superpose_over->getEnd());
                // date de fin de la première partie de l'événement superposé
                $event_superpose_over->setEnd($start);
                $em->persist($event_superpose_over);
                $em->persist($event_superpose_over2);
            }
            $events_superpose_erase = $this->getDoctrine()->getRepository(Event::class)->searchSuperposeErase($filtres, $datas['event-add-users'][0]);
            foreach ($events_superpose_erase as $event_superpose_erase){
                $em->remove($event_superpose_erase);
            }
        }


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