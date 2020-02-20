<?php

namespace App\UI\HTTP\REST\FormType;

use App\UI\HTTP\REST\DTO\OrderRequestDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

final class OrderRequestTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('establishment', EstablishmentType::class)
            ->add('catalog_flow', CatalogFlowType::class)
            ->add('table_identifier', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new NotNull()
                ]
            ])
            ->add('items', ItemCollectionType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrderRequestDto::class,
            'csrf_protection' => false,
        ]);
    }
}
