<?php

declare(strict_types=1);

namespace App\UI\HTTP\REST\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Uuid;

final class OrderCancelRequestTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('uuid', TextType::class, [
                'constraints' => [
                    new NotNull(),
                    new NotBlank(),
                    new Uuid()
                ]
            ])
        ;
    }
}
