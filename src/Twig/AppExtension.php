<?php

namespace App\Twig;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('tour_category', [$this, 'tourCategory']),
        ];
    }

    public function tourCategory($category) {

        $tour_categories = [
            'cultural-tour' => 'Cultural Tour',
            'festival-tour' => 'Festival Tour',
            'adventure-tour' => 'Adventure Tour',
            'trekking-tour' => 'Trekking Tour',
        ];

        return $tour_categories[$category];
    }
}