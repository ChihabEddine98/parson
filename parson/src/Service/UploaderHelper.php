<?php


namespace App\Service;


use Gedmo\Sluggable\Util\Urlizer;
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

    public function uploadCourseImg(UploadedFile $file) :string
    {
        $dest = $this->uploadsPath.'/course_img';
        $imgOriginalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $newImgName = Urlizer::urlize($imgOriginalName) . '-' . uniqid() . '.' . $file->guessExtension();

        $file->move(
            $dest,
            $newImgName
        );

        return $newImgName;

    }
    public function uploadUserImg(UploadedFile $file) :string
    {
        $dest = $this->uploadsPath.'/user_img';
        $imgOriginalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $newImgName = Urlizer::urlize($imgOriginalName) . '-' . uniqid() . '.' . $file->guessExtension();

        $file->move(
            $dest,
            $newImgName
        );

        return $newImgName;

    }

}