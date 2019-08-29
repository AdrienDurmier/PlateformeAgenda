<?php

namespace App\Controller\Agenda;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\APIUsers;
use App\Entity\User;

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

        $user_selected = $this->getUser();
        if ($request->isMethod('POST')) {
            $datas = $request->request->all();
            // Attention: reste limité au utilisateur connus de la plateforme et non de l'api (il faut que chaque utilisateur ce soit connecté par exemple pour créer un compte).
            $user_selected = $this->getDoctrine()->getRepository(User::class)->findOneByUsername($datas['user']);
        }

        return $this->render('agenda/calendar/index.html.twig', [
            'users' => $users,
            'user_selected' => $user_selected,
        ]);
    }
}