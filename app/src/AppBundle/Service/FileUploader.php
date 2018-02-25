<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader {


    public function upload(UploadedFile $file, $path)
    {
        $fileName = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file->getClientOriginalName());
        $fileName = $this->sanitizeFilename($fileName);

        $fileName = $fileName . '.' . $file->guessExtension();

        return $file->move($path, $fileName);
    }

    public function sanitizeFilename($fileName)
    {
        $fileName = strtr(utf8_decode($fileName), utf8_decode(
            'ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿĘÓĄŚŁŻŹĆŃęóąśłżźćń'),
            'SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyyEOASLZZCNeoaslzzcn');
        $fileName = str_replace(' ', '-', $fileName);
        $fileName = preg_replace('/[^A-Za-z0-9\-]/', '', $fileName);

        return $fileName;
    }

}