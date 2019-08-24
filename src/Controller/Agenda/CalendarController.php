<?php

namespace App\Controller\Agenda;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Agenda\Events;

class CalendarController extends AbstractController
{
    /**
     * @Route("/admin/agenda/calendar", name="agenda.calendar.index")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {


        return $this->render('agenda/calendar/index.html.twig', [

        ]);
    }
}