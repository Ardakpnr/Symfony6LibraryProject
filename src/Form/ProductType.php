<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,[
                'attr' =>['autofocus' => true, 'class' =>'input'],
                
            ])
            ->add('keywords', TextType::class,[
                'attr' =>['autofocus' => true, 'class' =>'input'],
                
            ])
            ->add('description',TextType::class,[
                'attr' =>['autofocus' => true, 'class' =>'input'],
                
            ])
            // ->add('image', FileType::class,[
            //     'attr' =>['autofocus' => true, 'class' =>'input'],
                
            // ])
            ->add('detail',TextType::class,[
                'attr' =>['autofocus' => true, 'class' =>'input'],
                
            ])
            ->add('price', NumberType::class,[
                'attr' =>['autofocus' => true, 'class' =>'input'],
                
            ])
            ->add('quantiy', NumberType::class,[
                'attr' =>['autofocus' => true, 'class' =>'input'],
            ])
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    'True' => 'True',
                    'False' => 'False',
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Product Image:',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid Image File',
                    ])
                ],
            ])
            //->add('category')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
