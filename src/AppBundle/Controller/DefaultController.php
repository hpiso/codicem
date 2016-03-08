<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Item;
use AppBundle\Form\ItemType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $items = $em->getRepository('AppBundle:Item')->findAll();

        $item = new Item();
        $form = $this->createForm(new ItemType(), $item);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($item);
            $em->flush();
        }


        return $this->render('AppBundle:App/index.html.twig', [
            'items' => $items,
            'form'  => $form->createView()
        ]);
    }
}
