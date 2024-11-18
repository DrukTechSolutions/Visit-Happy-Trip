<?php

namespace App\Service;

class CategoryService
{
    public function categoryAndSubCategory($categories)
    {
        $tourCategories = [];

        foreach ($categories as $category) {
            if (!$category->getSubCategory()) {
                $tourCategories['tour_categories'][$category->getId()] = [
                    'category' => $category->getCategory(),
                    'category_id' => $category->getId(),
                    'sub_categories' => []
                ];
            } else {
                $tourCategories['tour_categories'][$category->getSubCategory()->getId()]['sub_categories'][] = [
                    'sub_category' =>  $category->getCategory(),
                    'sub_category_id' => $category->getId()
                ];
            }
        }

        return $tourCategories;
    }

    public function parentCategoryTours($categories)
    {
        $tourCategories = [];
        foreach ($categories as $category) {
            if (!$category->getSubCategory()) {
                $tourCategories['tour_categories'][$category->getId()] = [
                    'category' => $category->getCategory(),
                    'category_id' => $category->getId(),
                    'category_slug' => $category->getSlug(),
                    'total_tours' => $this->countNoOfTours($category->getTourCategories()),
                    'image' => $this->getTourImage($category->getTourCategories()),
                ];
            }
        }

        return $tourCategories;
    }

    private function countNoOfTours($categories)
    {
        $tours = [];
        foreach ($categories as $category) {
            foreach ($category->getTourPackage() as $package) {
                $tours[$package->getId()] = $package;
            }
        }
        return count($tours);
    }

    private function getTourImage($categories)
    {
        $image = '';
        foreach ($categories as $category) {
            foreach ($category->getTourPackage() as $package) {
                foreach ($package->getImages() as $image) {
                    $image = $image->getImageName();
                }
            }
        }
        return $image;
    }
}
