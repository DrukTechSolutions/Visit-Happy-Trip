<?php

namespace App\Event;

use App\Entity\TourPackage;
use Symfony\Contracts\EventDispatcher\Event;

class DeleteCategoryEvent extends Event
{
   
    public function __construct(private TourPackage $tours)
    {
        
    }

    public function getToursPackage()
    {
        return $this->tours;
    }
}