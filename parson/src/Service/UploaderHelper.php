<?php


namespace App\Service;


use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderHelper
{
    /**
     * @var string
     */
    private $uploadsPath;


    /**
     * UploaderHelper constructor.
     */
    public function __construct(string $uploadsPath)
    {
        // On le trouve à services.yaml ( bind )
        $this->uploadsPath = $uploadsPath;
    }

    public function uploadCourseImg(File $file) :string
    {
        $dest = $this->uploadsPath.'/course_img';
        if ($file instanceof UploadedFile)
        {
            $fileName=$file->getClientOriginalName();
        }
        else{
            $fileName=$file->getFilename();
        }
        $imgOriginalName = pathinfo($fileName, PATHINFO_FILENAME);
        $newImgName = Urlizer::urlize($imgOriginalName) . '-' . uniqid() . '.' . $file->guessExtension();

        $file->move(
            $dest,
            $newImgName
        );

        return $newImgName;

    }
    public function uploadUserImg(File $file) :string
    {
        $dest = $this->uploadsPath.'/user_img';
        if ($file instanceof UploadedFile)
        {
            $fileName=$file->getClientOriginalName();
        }
        else{
            $fileName=$file->getFilename();
        }
        $imgOriginalName = pathinfo($fileName, PATHINFO_FILENAME);
        $newImgName = Urlizer::urlize($imgOriginalName) . '-' . uniqid() . '.' . $file->guessExtension();

        $file->move(
            $dest,
            $newImgName
        );

        return $newImgName;

    }

}