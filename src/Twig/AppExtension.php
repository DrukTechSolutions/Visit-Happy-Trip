<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('is_active_route', [$this, 'isActiveRoute']),
        ];
    }

    public function isActiveRoute($routes)
    {
        dump($routes);
    }
}
