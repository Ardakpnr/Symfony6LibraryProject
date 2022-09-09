<?php

namespace App\Form;

use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',  TextType::class, [
                'attr' => ['autofocus'=> true, 'class' => 'form-control'],
                
            ])
            ->add('email', EmailType::class,[
                'attr' => ['autofocus'=> true, 'class' => 'form-control']
            ])
            ->add('phone', TextType::class,[
                'attr' => ['autofocus'=> true, 'class' => 'form-control']
            ])
            ->add('subject', TextType::class,[
                'attr' => ['autofocus'=> true, 'class' => 'form-control']
            ])
            ->add('message', TextareaType::class,[
                'attr' => ['autofocus'=> true, 'class' => 'form-control']
            ])
            ->add('Gonder', SubmitType::class,[
                'attr' => ['autofocus'=> true, 'class' => 'btn btn-primary py-2 px-4']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
