<?php

namespace App\Controller\Aide;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Aide\Tutorial;

class TutorialController extends AbstractController
{
    /**
     * @Route("/admin/aide/tutorials", name="aide.tutorial.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
        $tutorials = $this->getDoctrine()->getRepository(Tutorial::class)->findAll();
        return $this->render('aide/tutorial/index.html.twig', [
            'tutorials' => $tutorials
        ]);
    }

    /**
     * @Route("/admin/aide/tutorial/show/{id}", name="aide.tutorial.show", methods="GET|POST")
     * @param Tutorial $tutorial
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function show(Tutorial $tutorial)
    {
        return $this->render('aide/tutorial/show.html.twig', [
            'entity'  =>  $tutorial,
        ]);
    }

    /**
     * @Route("/admin/aide/tutorial/new", name="aide.tutorial.new", methods="GET|POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if ($request->isMethod('POST')) {
            $datas = $request->request->all();
            $tutorial = new Tutorial();
            $tutorial->setTitle($datas['title']);
            $tutorial->setContent($datas['content']);
            $tutorial->setPublic(isset($datas['public'])?$datas['public']:0);
            $tutorial->setAuthor($this->getUser());
            $em->persist($tutorial);
            $em->flush();
            $this->addFlash('success', "Contenu créé avec succès");
            return $this->redirectToRoute('aide.tutorial.index');
        }

        return $this->render('aide/tutorial/new.html.twig');
    }

    /**
     * @Route("/admin/aide/tutorial/edit/{id}", name="aide.tutorial.edit", methods="GET|POST")
     * @param Tutorial $tutorial
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Tutorial $tutorial, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if ($request->isMethod('POST')) {
            $datas = $request->request->all();
            $tutorial->setTitle($datas['title']);
            $tutorial->setContent($datas['content']);
            $tutorial->setPublic(isset($datas['public'])?$datas['public']:0);
            $tutorial->setAuthor($this->getUser());
            $em->persist($tutorial);
            $em->flush();
            $this->addFlash('success', "Contenu modifié avec succès");
            return $this->redirectToRoute('aide.tutorial.index');
        }

        return $this->render('aide/tutorial/edit.html.twig', [
            'entity'  =>  $tutorial,
        ]);
    }

    /**
     * @Route("/admin/aide/tutorial/{id}", name="aide.tutorial.delete", methods="DELETE")
     * @param Tutorial $tutorial
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Tutorial $tutorial, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if($this->isCsrfTokenValid('delete_tutorial', $request->get('_token'))){
            $em->remove($tutorial);
            $em->flush();
            $this->addFlash('success', "Contenu supprimé avec succès");
        }
        return $this->redirectToRoute('aide.tutorial.index');
    }

}