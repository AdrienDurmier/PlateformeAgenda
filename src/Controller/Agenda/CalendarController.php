<?php

namespace App\Controller\Agenda;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\APIUsers;

class CalendarController extends AbstractController
{
    /**
     * @Route("/admin/agenda/calendar", name="agenda.calendar.index")
     * @param APIUsers $apiUsers
     * @param Request $request
     * @return Response
     * @throws \Doctrine\DBAL\Exception\ServerException
     */
    public function index(APIUsers $apiUsers ,Request $request)
    {
        $api_users_response = $apiUsers->interroger('get', 'users');

        $users = [];
        if($api_users_response->code == 200){
            $users = $api_users_response->body;
        }

        return $this->render('agenda/calendar/index.html.twig', [
            'users' => $users,
        ]);
    }
}