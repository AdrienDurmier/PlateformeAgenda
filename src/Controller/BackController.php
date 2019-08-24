<?php

namespace App\Controller;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BackController extends AbstractController
{
    /**
     * @Route("/admin", name="back.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        return $this->render('back/index.html.twig', [
        ]);
    }

    /**
     * @Route("/admin/settings", name="back.settings")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function settings()
    {
        return $this->render('back/settings.html.twig', [
        ]);
    }
    
}