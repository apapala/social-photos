<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Photo;
use AppBundle\Entity\Tag;
use AppBundle\Entity\User;
use AppBundle\Entity\UserTagPhoto;

/**
 * UserTagPhotoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserTagPhotoRepository extends \Doctrine\ORM\EntityRepository
{

    public function findAllTagsByUsers($userIds)
    {
        // More on query builder: http://docs.doctrine-project.org/projects/doctrine-orm/en/latest/reference/query-builder.html

        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
            ->select('t.name')
            ->from('AppBundle:UserTagPhoto', 'utp')
            ->leftJoin('utp.tag', 't')
            ->andWhere('utp.user IN (:ids)')
            ->setParameter('ids', $userIds);

        $result = $qb->getQuery()->getResult();

        $tags = [];
        foreach ($result as $userTagPhoto) {
            $tags[] = $userTagPhoto['name'];
        }

        $tagOccurences = array_count_values($tags);

        arsort($tagOccurences);

        return $tagOccurences;
    }

    public function removeTagsOnPhoto(Photo $photo, User $user)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb
            ->delete('AppBundle:UserTagPhoto', 'utp')
            ->where('utp.user = :user')
            ->andWhere('utp.photo = :photo')
            ->setParameter('user', $user)
            ->setParameter('photo', $photo);

        $qb->getQuery()->getResult();
    }

    public function addTagsToPhoto($tagsArray, Photo $photo, User $user, $withFlush = false)
    {
        $em = $this->getEntityManager();

        $tagRepository = $this->getEntityManager()->getRepository(Tag::class);

        foreach ($tagsArray as $tagName) {
            $tag = $tagRepository->createTag($tagName);

            $userTagPhoto = new UserTagPhoto();
            $userTagPhoto->setPhoto($photo);
            $userTagPhoto->setUser($user);
            $userTagPhoto->setTag($tag);

            $em->persist($userTagPhoto);

        }

        if ($withFlush)
            $em->flush();
    }

    public function findByPhotoAndUser(Photo $photo, User $user)
    {
        return $this->findBy(['photo' => $photo, 'user' => $user]);
    }

}
