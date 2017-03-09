<?php

namespace AppBundle\Controller;

use AppBundle\Document\Product;
use AppBundle\Entity\Order;
use AppBundle\Form\OrderType;
use AppBundle\Form\ProductType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
        ]);
    }


    /**
     * Creates a new oncologiascheda entity.
     *
     * @Route("/new", name="upload_new", options={"expose"=true})
     * @Method({"GET", "POST"})
     *
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function newAction(Request $request)
    {


        $entity = new Order();

        $form = $this->createForm(OrderType::class, $entity, array(
            'action' => $this->generateUrl('upload_new')));

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {

                $em = $this->getDoctrine()->getManager();

                $em->persist($entity);
                $em->flush();
//print_r($entity->getProduct()->getFilename());
//die;

                return $this->redirect($this->generateUrl('homepage'));
            } else {
                return new Response(
                    $this->renderView(':default:new.html.twig', array(
                        'entity' => $entity,
                        'form' => $form->createView(),

                    ))
                    , 409);
            }
        }

        return $this->render(':default:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),

        ));


    }


    /**
     * @Route("/list/{id}", name="list")
     */
    public function listAction(Request $request, $id)
    {

        $entity = $this->getDoctrine()->getEntityManager()->getRepository('AppBundle:Order')->find($id);

        $file = $this->get('doctrine.odm.mongodb.document_manager')->getRepository('AppBundle:Product')->find($entity->getProductId());

//        return new Response($entity->getProduct()->getFile()->getBytes(), 200, array('content-type' => $entity->getProduct()->getMimeType()));
        return new Response($file->getFile()->getBytes(), 200, array('content-type' => $file->getMimeType()));


    }


}
