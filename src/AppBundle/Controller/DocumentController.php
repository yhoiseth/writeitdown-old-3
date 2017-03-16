<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Document;
use AppBundle\Form\DocumentType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DocumentController extends Controller
{
    /**
     * @Route("/new")
     */
    public function newAction(Request $request)
    {
        $document = new Document();
        $form = $this->createForm(DocumentType::class, $document);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $document = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($document);
            $entityManager->flush();


            return $this->redirectToRoute('app_document_edit', ['id' => $document->getId()]);
        }

        return $this->render('AppBundle:Document:new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param string $id
     * @Route("/{id}/edit", name="app_document_edit")
     * @return Response
     */
    public function editAction(string $id)
    {
        return new Response();
    }
}
