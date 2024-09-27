<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('createdAt', DateTimeType::class, [
                'widget' => 'single_text',
                'data' => new \DateTime(),  // Date par défaut : maintenant
            ])
            ->add('image')
            ->add('love')
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            ->add('tag', EntityType::class, [
                'class' => Tag::class,
                'choice_label' => 'name',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
