<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Item;
use AppBundle\Form\ItemType;
use Doctrine\ORM\EntityNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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

        $nbCalorie = 0;
        foreach ($items as $item) {
            $nbCalorie += $item->getItemType()->getCalorie();
        }

        $item = new Item();
        $form = $this->createForm(new ItemType(), $item);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($item);
            $em->flush();
        }

        return $this->render('AppBundle:App/index.html.twig', [
            'items' => $items,
            'form'  => $form->createView(),
            'nbCalorie' => $nbCalorie,
        ]);
    }

    /**
     * Deletes a Actualite entity.
     *
     * @Route("/conso-delete/{id}", name="conso_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em   = $this->getDoctrine()->getManager();
            $item = $em->getRepository('AppBundle:Item')->find($id);

            if (!$item) {
                throw new EntityNotFoundException('Entity item not found');
            }

            $em->remove($item);
            $em->flush();

            $this->redirectToRoute('homepage');
        }

        return $this->render('AppBundle:App/delete-form.html.twig', [
            'form'  => $form->createView()
        ]);
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, ['label' => 'Supprimer quand même', 'attr' => ['class' => 'button']])
            ->getForm()
            ;
    }
}
