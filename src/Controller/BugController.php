<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\MessageBug;
use App\Notification\NotificationBug;
use App\Service\EasyUploads;

class BugController extends AbstractController
{
    /**
     * @Route("/admin/bug", name="bug.index")
     * @param Request $request
     * @param EasyUploads $easyUploads
     * @param NotificationBug $notification
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, EasyUploads $easyUploads, NotificationBug $notification): Response
    {
        $em = $this->getDoctrine()->getManager();

        if ($request->isMethod('POST')) {
            $datas = $request->request->all();
            // Si upload d'un fichier
            $message = new MessageBug();
            $message->setContent($datas['content']);
            $message->setUser($this->getUser());
            $file = $request->files->get('filename');
            if ($file) {
                $file_upload = $easyUploads->file($file, 'path_uploads_message_bug');
                $message->setFilename($file_upload);
            }
            $em->persist($message);
            $em->flush();

            $notification->notify($message);
            $this->addFlash('success', 'Votre signalement a bien été envoyé');
        }

        return $this->render('bug/index.html.twig');
    }
}