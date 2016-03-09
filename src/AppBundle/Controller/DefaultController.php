<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Item;
use AppBundle\Entity\ItemType;
use AppBundle\Form\ItemType as formItemType;
use AppBundle\Form\ItemTypeType;
use Doctrine\ORM\EntityNotFoundException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    const CALORIE_MAX = 2500;

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $user = $this->getUser();

        $em = $this->getDoctrine()->getManager();
        $items = $em->getRepository('AppBundle:Item')->findByUser(
            ['user' => $user]
        );

        $nbCalorie = 0;
        foreach ($items as $item) {
            $nbCalorie += $item->getItemType()->getCalorie();
        }

        $levelPercentage = ($nbCalorie / self::CALORIE_MAX) * 100;

        $item = new Item();
        $form = $this->createForm(new formItemType(), $item);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $item->setUser($user);

            $em->persist($item);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('AppBundle:App/index.html.twig', [
            'items'           => $items,
            'form'            => $form->createView(),
            'nbCalorie'       => $nbCalorie,
            'levelPercentage' => $levelPercentage,
        ]);
    }

    /**
     * @Route("/conso-delete/{id}", name="conso_delete")
     * @Method("delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($request->isMethod('delete')) {
            $em   = $this->getDoctrine()->getManager();
            $item = $em->getRepository('AppBundle:Item')->find($id);

            if (!$item) {
                throw new EntityNotFoundException('Entity item not found');
            }

            $em->remove($item);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('AppBundle:App/delete-form.html.twig', [
            'form'  => $form->createView()
        ]);
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('conso_delete', ['id' => $id]))
            ->setMethod('delete')
            ->add('submit', SubmitType::class, ['label' => 'Supprimer quand même', 'attr' => ['class' => 'button']])
            ->getForm()
            ;
    }

    /**
     *
     * @Route("/ajouter-conso", name="conso_add")
     */
    public function createAction(Request $request)
    {
        $itemType = new ItemType();

        $form = $this->createForm(new ItemTypeType(), $itemType);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $itemType->setCreatedBy($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($itemType);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('AppBundle:App/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     *
     * @Route("/classement", name="classement")
     */
    public function classementAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('UserBundle:User')->findAll();

        dump($users);die;

        return $this->render('AppBundle:App/classement.html.twig');
    }
}
