<?php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResSuppliolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Class UserType
 *
 * @package AppBundle\Form
 */
class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options, array $parameters = null): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'required'    => true,
                'constraints' => [
                    new NotBlank(),
                ],
                'label' => false,
                'attr' => [
                    'placeholder' => 'First Name',
                ]
            ])
            ->add('email', EmailType::class, [
                'required'    => true,
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                ],
                'label' => false,
                'attr' => [
                    'placeholder' => 'Email',
                ]
            ]);

        /* Dependent fields */
        if (!empty($options['validation_groups']) && in_array('withPassword', $options['validation_groups'])) {

            /* Add password field with constraints (create user action) */
            $builder
                ->add('password', PasswordType::class, [
                    'required'    => true,
                    'constraints' => [
                        new NotBlank(),
                        new Length(['min' => 5]),
                    ],
                    'label' => false,
                    'attr' => [
                        'placeholder' => 'Password',
                    ]
                ]);

        } else {

            /* Add password field without constraints (edit user action) */
            $entity = $builder->getData();

            $builder
                ->add('password', PasswordType::class, [
                    'required' => false,
                    'label' => false,
                    'attr' => array(
                        'placeholder' => 'Password',
                        'class' => 'form-control' // Moved to form theme
                    ),
                ]);

//                ->add('role', ChoiceType::class, [
//                    /* Important to use below statement to add external fields into form based on Entity */
//                    "mapped"            => false,
//                    'required'          => true,
//                    'constraints'       => [
//                        new NotBlank(),
//                    ],
//                    'choices'           => $entity->getRolesListForDropdown(),
//                    'preferred_choices' => $entity->getRoles(),
//                ]);
        }
    }


    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'         => User::class,
            'allow_extra_fields' => true,
        ]);
    }


    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'user';
    }
}
