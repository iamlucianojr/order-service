<?php

declare(strict_types=1);

namespace App\UI\HTTP\REST\FormType;

use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;

final class ItemCollectionType extends CollectionType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'empty_data' => [],
            'entry_type' => ItemType::class,
            'allow_add' => true,
            'constraints' => [
                new Count([
                    'min' => 1,
                    'minMessage' => 'The collection must contain at least {{ limit }} item.',
                ]),
            ],
        ]);
    }
}
