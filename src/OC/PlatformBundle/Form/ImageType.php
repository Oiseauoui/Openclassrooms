<?php
// src/OC/PlatformBundle/Form/ImageType.php

namespace OC\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder,
                              array $options)
    {
        $builder
            ->add('file', FileType::class)
            ->add('url', TextType::class)
            ->add('alt', TextType::class)


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'OC\PlatformBundle\Entity\Image'
        ));
    }
}
