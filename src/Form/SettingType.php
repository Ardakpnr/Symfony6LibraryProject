<?php

namespace App\Form;

use App\Entity\Setting;
use Symfony\Component\Form\AbstractType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SettingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('keywords')
            ->add('desceription')
            ->add('company')
            ->add('adress')
            ->add('phone')
            ->add('fax')
            ->add('email')
            ->add('smtpserver')
            ->add('smtpemail')
            ->add('smtppassword')
            ->add('smtpport')
            ->add('facebook')
            ->add('instegram')
            ->add('twitter')
            ->add('youtube')
            ->add('tiktok')
            ->add('aboutus', CKEditorType::class, array(
                'config'=> array(
                    'uiColor'=> '#ffffff',
                    'toolbar' =>'full',
                ),
                
            ))
            ->add('contact', CKEditorType::class, array(
                'config'=> array(
                    'uiColor'=> '#ffffff',
                    'toolbar' =>'full',
                ),
                
            ))
            ->add('reference', CKEditorType::class, array(
                'config'=> array(
                    'uiColor'=> '#ffffff',
                    'toolbar' =>'full',
                ),
                
            ))
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    'True' => 'True',
                    'False' => 'False',
                    
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Setting::class,
        ]);
    }
}
