<?php

namespace AppBundle\Form;

use AppBundle\Entity\Photo;
use AppBundle\Entity\UserTagPhoto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

/**
 * Class UserType
 *
 * @package AppBundle\Form
 */
class PhotoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options, array $parameters = null): void
    {
        $builder
            ->add('filename', FileType::class, [
                'constraints' => [
                    new File([
                        'maxSize' => '10M'
                    ]),
                ],
            ])
            ->add('tags', TextType::class, [
                'mapped' => false,
                'attr' => [],
                'required' => false
            ]);
    }


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Photo::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'photo';
    }
}
