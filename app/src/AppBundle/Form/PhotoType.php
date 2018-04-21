<?php

namespace AppBundle\Form;

use AppBundle\Form\DataTransformer\UserTagPhotosTransformer;
use AppBundle\Entity\Photo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File as FileConstraint;

/**
 * Class UserType
 *
 * @package AppBundle\Form
 */
class PhotoType extends AbstractType
{

    /**
     * @var UserTagPhotosTransformer
     */
    private $userTagPhotosTransformer;

    /**
     * PhotoType constructor.
     * @param UserTagPhotosTransformer $userTagPhotosTransformer
     */
    public function __construct(UserTagPhotosTransformer $userTagPhotosTransformer)
    {
        $this->userTagPhotosTransformer = $userTagPhotosTransformer;
    }
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options, array $parameters = null): void
    {

        $builder
            ->add('filename', FileType::class, [
                'constraints' => [
                    new FileConstraint([
                        'maxSize' => '10M'
                    ]),
                ],
                'required' => false
            ])
            ->add('image', FileType::class, [
                'constraints' => [
                    new FileConstraint([
                        'maxSize' => '10M'
                    ]),
                ],
                'required' => false
            ])
            ->add('thumbnail', FileType::class, [
                'constraints' => [
                    new FileConstraint([
                        'maxSize' => '10M'
                    ]),
                ],
                'required' => false
            ])
            ->add('userTagPhotos', TextType::class, [
                'mapped' => true,
                'attr' => [],
                'required' => false,
            ]);

        $builder->get('userTagPhotos')->addModelTransformer($this->userTagPhotosTransformer);

        if (isset($options['validation_groups']) && in_array('edit', $options['validation_groups'])) {
            $builder->addModelTransformer(new CallbackTransformer(
                function($photo) {

                    if ($photo->getFilename() !== null) {
                        $photo->setFilename(new File($photo->getFilename()));
                    }

                    if ($photo->getImage() !== null) {
                        $photo->setImage(new File($photo->getImage()));
                    }

                    if ($photo->getThumbnail() !== null) {
                        $photo->setThumbnail(new File($photo->getThumbnail()));
                    }

                    return $photo;

                },
                function($photo) {

                    return $photo;

                }
            ));

        }
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
