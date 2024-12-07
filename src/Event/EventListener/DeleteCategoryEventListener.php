<?php

namespace App\Event\EventListener;

use App\Event\DeleteCategoryEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

#[AsEventListener(event: DeleteCategoryEvent::class, method: 'onDeleteSubCategory')]
class DeleteCategoryEventListener
{
    public function __construct(private EntityManagerInterface $em)
    {
        
    }
    public function onDeleteSubCategory(DeleteCategoryEvent $deleteCategoryEvent)
    {
        $tours = $deleteCategoryEvent->getToursPackage();
        $tours->setTourCategory(null);
        $this->em->persist($tours);
        $this->em->flush();

    }
}