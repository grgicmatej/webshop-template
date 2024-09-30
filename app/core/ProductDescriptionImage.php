<?php

declare(strict_types=1);

class ProductDescriptionImage
{
    public static function imageUpload(): void
    {
        reset ($_FILES);
        $temp = current($_FILES);

        $currentDir=getcwd();
        $uploadDirectory='assets/images/productDescriptionImages/';

        $fileName=$temp['name'];
        $fileTmpName =$temp['tmp_name'];
        $fileExtension=pathinfo($fileName, PATHINFO_EXTENSION);
        $fileName1=uniqid().".".$fileExtension;
        $uploadPath=$currentDir .'/'. $uploadDirectory .$fileName1;

        $didUpload=move_uploaded_file($fileTmpName, $uploadPath);

        echo json_encode(array('location' => App::config("url") . $uploadDirectory .$fileName1));
    }
}