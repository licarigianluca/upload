<?php

namespace AppBundle\EventListener;


use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ORM\Event\LifecycleEventArgs;

class MyEventSubscriber
{
    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $order = $eventArgs->getEntity();
        $em = $eventArgs->getEntityManager();

        $product = $order->getProduct();


        $file = $product->getFile();

        $product->setFile($file->getPathname());
        $product->setFilename($file->getClientOriginalName());
        $product->setMimeType($file->getClientMimeType());

        $this->dm->persist($product);
        $this->dm->flush();

        $order->setProductId($product->getId());

//        $productReflProp = $em->getClassMetadata('AppBundle:Order')
//            ->reflClass->getProperty('productId');
//        $productReflProp->setAccessible(true);
//        $productReflProp->setValue(
//            $order, $this->dm->getReference('AppBundle:Product', $product->getId())
//        );
    }


    public function postLoad(LifecycleEventArgs $eventArgs)
    {
//        $order = $eventArgs->getEntity();
//        $em = $eventArgs->getEntityManager();
//        $productReflProp = $em->getClassMetadata('AppBundle:Order')
//            ->reflClass->getProperty('product');
//        $productReflProp->setAccessible(true);
//        $productReflProp->setValue(
//            $order, $this->dm->getReference('AppBundle:Product', $order->getProductId())
//        );
    }
}