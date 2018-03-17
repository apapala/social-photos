<?php

namespace AppBundle\Form\DataTransformer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class UserTagPhotosTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Transforms an object (issue) to a string (number).
     *
     * @param  Issue|null $issue
     * @return string
     */
    public function transform($userTagPhotos)
    {
        if ($userTagPhotos->isEmpty()) {
            return $userTagPhotos;
        }
        
        $arrayTags = [];

        foreach ($userTagPhotos as $userTagPhoto) {
            $arrayTags[] = $userTagPhoto->getTag()->getName();
        }

        $stringTags = implode(',', $arrayTags);

        return $stringTags;
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param  string $issueNumber
     * @return Issue|null
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($userTagPhotos)
    {
        // return
        return $userTagPhotos;
    }
}