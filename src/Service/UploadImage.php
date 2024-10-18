<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploadImage
{
    public function __construct( private string $targetDirectory, private SluggerInterface $slug)
    {
        
    }
    public function uploadImage(UploadedFile $image) : string {

        $originalImageName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        $slugImageName = $this->slug->slug($originalImageName);
        $imageName = $slugImageName.'-'.uniqid().'.'.$image->guessExtension();

        $image->move(
            $this->getTargetDirectory(),
            $imageName
        );

        return $imageName;
    }

    public function getTargetDirectory() : string {
        return $this->targetDirectory;
    }
}