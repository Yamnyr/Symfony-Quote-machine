<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Quote;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuoteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextType::class, [
                'label' => 'contenu',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrez la nuvelle citation',
                ],
            ])
            ->add('meta', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'Entrez l\'origine de la citation',
                ],
            ])
            ->add('category', EntityType::class, [
                'label' => 'categorie',
                'class' => Category::class,
                'required' => false,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quote::class,
        ]);
    }
}
