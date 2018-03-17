<?php namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;

class FileManager {

    /**
     * @var EntityManager
     */
    private $entityManager;

    private $blockPrefix;

    private $path;

    private $formFields = [];

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Move, upload file based on field/s from request and entity
     *
     * @param $request
     * @param $entity
     * @return mixed $entity
     */
    public function uploadRequestFile($request, $entity)
    {

        foreach ($this->formFields as $fieldName) {

            $ucfirstFieldName = ucfirst($fieldName);

            // Check if $request has new file
            if ($request->files->get($this->blockPrefix)[$fieldName]) {

                $handler = new UploadedFileHandler();

                $file = $handler->upload(call_user_func([$entity, "get" . $ucfirstFieldName]), $this->path);

                call_user_func([$entity, "set" . $ucfirstFieldName], $file);

            } else {
                // Empty request

                $originalEntity = $this->entityManager->getUnitOfWork()->getOriginalEntityData($entity);

                if (!isset($originalEntity[$fieldName])) {
                    call_user_func([$entity, "set" . $ucfirstFieldName], null);
                } else {
                    $originalEntityFieldName = $originalEntity[$fieldName];

                    call_user_func([$entity, "set" . $ucfirstFieldName], $originalEntityFieldName);
                }

            }

        }

        return $entity;
    }

    /**
     * @param mixed $blockPrefix
     * @return FileManager
     */
    public function setBlockPrefix($blockPrefix)
    {
        $this->blockPrefix = $blockPrefix;
        return $this;
    }

    /**
     * @param array $formFields
     * @return FileManager
     */
    public function setFormFields($formFields)
    {
        $this->formFields = $formFields;
        return $this;
    }

    /**
     * @param mixed $path
     * @return FileManager
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }
}