<?php

namespace AppBundle\Form;

use AppBundle\Entity\UserTagPhoto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 *
 * @package AppBundle\Form
 */
class UserTagPhotoType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options, array $parameters = null): void
    {
        $builder
            ->add('tags', TextType::class, [
                'mapped' => false,
                'attr' => [],
                'required' => false,
            ]);
    }


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserTagPhoto::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'user_tag_photo';
    }
}
