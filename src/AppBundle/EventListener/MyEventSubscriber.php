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

//        $product->setFileName('mioFile.zip');

        $this->dm->persist($product);
        $this->dm->flush();

        $productReflProp = $em->getClassMetadata('AppBundle:Order')
            ->reflClass->getProperty('product');
        $productReflProp->setAccessible(true);
        $productReflProp->setValue(
            $order, $this->dm->getReference('AppBundle:Product', 2)
        );
    }
}