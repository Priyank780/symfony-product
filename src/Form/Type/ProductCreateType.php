<?php

namespace App\Form\Type;

use App\Form\ProductCreateModal;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductCreateType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Product Name',
                'attr' => [
                    'placeholder' => 'IPhone 12',
                    'minLength' => 3,
                    'maxLength' => 200,
                ],
                'help' => '',
            ])
            ->add('description', TextareaType::class, [
                'required' => true,
                'label' => 'Product Description',
                'attr' => [
                    'rows' => 5,
                    'minLength' => 10,
                    'maxLength' => 65536,
                ],
                'help' => '',
            ])
            ->add('price', NumberType::class, [
                'required' => true,
                'label' => 'Product Price',
                'help' => '',
            ])
            ->add('create', SubmitType::class, [
                'label' => 'Create',
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductCreateModal::class,
            'csrf_protection' => true,
            'allow_extra_fields' => true,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return '';
    }
}
