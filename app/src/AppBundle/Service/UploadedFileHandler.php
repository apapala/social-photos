<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadedFileHandler {

    /**
     * Upload file to given path
     *
     * @param UploadedFile $file
     * @param $path
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    public function upload(UploadedFile $file, $path)
    {
        // Remove extension
        $fileName = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file->getClientOriginalName());

        // Sanitize string
        $fileName = $this->sanitizeFilename($fileName);

        // Add guessed extension
        $fileName = $fileName . '.' . $file->guessExtension();

        return $file->move($path, $fileName);
    }


    /**
     * Sanitize any string for filenmae purposes
     * Adds random string at the end of it
     *
     * @param $fileName
     * @param bool $withRandom Should add random string at the end
     * @return mixed|null|string|string[]
     */
    public function sanitizeFilename($fileName, $withRandom = true)
    {
        $fileName = strtr(utf8_decode($fileName), utf8_decode(
            'ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿĘÓĄŚŁŻŹĆŃęóąśłżźćń'),
            'SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyyEOASLZZCNeoaslzzcn');
        $fileName = str_replace(' ', '-', $fileName);
        $fileName = preg_replace('/[^A-Za-z0-9\-]/', '', $fileName);

        if ($withRandom)
            $fileName = $fileName . '-' . rand(1000000000, 9999999999);

        return $fileName;
    }

}