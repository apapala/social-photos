<?php

namespace AppBundle\Form;

use AppBundle\Entity\Grade;
use AppBundle\Entity\Photo;
use AppBundle\Entity\UserGradePhoto;
use AppBundle\Entity\UserTagPhoto;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

/**
 *
 * @package AppBundle\Form
 */
class UserGradePhotoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options, array $parameters = null): void
    {
        $builder
            ->add('grade', EntityType::class, [
                'mapped' => false,
                'class' => Grade::class,
                'choice_label' => 'grade'
            ]);
    }


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserGradePhoto::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'user_grade_photo';
    }
}
