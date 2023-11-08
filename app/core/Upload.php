<?php

declare(strict_types=1);

class Upload
{
    public static ?string $fileName='';

    public static function UploadPhoto($products = false): void
    {
        if (!empty($_FILES['myfile']['name'])){
            $currentDir=getcwd();

            $uploadDirectory='/assets/images/contentImages/';

            if (true === $products) {
                $uploadDirectory='/assets/images/productImages/';
            }

            $fileName=$_FILES['myfile']['name'];
            $fileTmpName =$_FILES['myfile']['tmp_name'];
            $fileExtension=pathinfo($fileName, PATHINFO_EXTENSION);
            $fileName1=uniqid().".".$fileExtension;
            $uploadPath=$currentDir . $uploadDirectory .$fileName1;

            $didUpload=move_uploaded_file($fileTmpName, $uploadPath);
            self::$fileName=$fileName1;
        }else{
            self::$fileName=null;
        }
    }

    public static function GetFileName(): ?string
    {
        return self::$fileName;
    }

}
