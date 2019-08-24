<?php

namespace App\Controller\Aide;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Aide\Glossaire;

class GlossaireController extends AbstractController
{
    /**
     * @Route("/admin/aide/glossaire", name="aide.glossaire.index")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $glossaires = $this->getDoctrine()->getRepository(Glossaire::class)->search();

        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $datas = $request->request->all();

            $glossaire = new Glossaire();
            $glossaire->setTitle($datas['title']);
            $glossaire->setContent($datas['content']);
            $em->persist($glossaire);
            $em->flush();
            $this->addFlash('success', "Définition ajoutée avec succès");
            return $this->redirectToRoute('aide.glossaire.index');
        }

        return $this->render('aide/glossaire/index.html.twig', [
            'glossaires' => $glossaires
        ]);
    }

    /**
     * @Route("/admin/aide/glossaire/edit", name="aide.glossaire.edit", methods="POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function edit(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $datas = $request->request->all();
        $glossaire = $this->getDoctrine()->getRepository(Glossaire::class)->find($datas['modal_id']);
        $glossaire->setTitle($datas['modal_title']);
        $glossaire->setContent($datas['modal_content']);
        $em->persist($glossaire);
        $em->flush();
        $this->addFlash('success', "Définition modifiée avec succès");
        return $this->redirectToRoute('aide.glossaire.index');
    }

    /**
     * @Route("/admin/aide/glossaire/delete", name="aide.glossaire.delete", methods="POST")
     * @param Request $request
     * @return JsonResponse
     */
    public function ajaxDelete(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $datas = $request->request->all();

        $glossaire = $this->getDoctrine()->getRepository(Glossaire::class)->find($datas['glossaire']);
        $em->remove($glossaire);
        $em->flush();

        $response = array(
            'success' => true,
        );
        return new JsonResponse($response);
    }
}