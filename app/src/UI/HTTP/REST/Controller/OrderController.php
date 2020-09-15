<?php

declare(strict_types=1);

namespace App\UI\HTTP\REST\Controller;

use App\Order\Application\Command\OrderCancelCommand;
use App\Order\Application\Command\OrderDeliverCommand;
use App\Order\Application\Command\OrderRedoCommand;
use App\Order\Application\Command\OrderRequestCommand;
use App\UI\HTTP\REST\DTO\OrderRequestDto;
use App\UI\HTTP\REST\FormType\OrderCancelRequestTypeForm;
use App\UI\HTTP\REST\FormType\OrderDeliverRequestTypeForm;
use App\UI\HTTP\REST\FormType\OrderRedoRequestTypeForm;
use App\UI\HTTP\REST\FormType\OrderRequestTypeForm;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\ControllerTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

final class OrderController extends AbstractController
{
    use ControllerTrait;

    private MessageBusInterface $commandBus;

    private LoggerInterface $logger;

    public function __construct(
        MessageBusInterface $commandBus,
        LoggerInterface $logger
    ) {
        $this->commandBus = $commandBus;
        $this->logger = $logger;
    }

    public function create(Request $request): Response
    {
        $orderRequestDto = new OrderRequestDto();
        $form = $this->createForm(OrderRequestTypeForm::class, $orderRequestDto);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $command = new OrderRequestCommand(
                Uuid::uuid4()->toString(),
                $orderRequestDto->getEstablishment(),
                $orderRequestDto->getCatalogFlow(),
                $orderRequestDto->getTableIdentifier(),
                $orderRequestDto->getItems()
            );

            $this->commandBus->dispatch($command);

            return new JsonResponse(['uuid' => $command->getOrderRequestId()], Response::HTTP_ACCEPTED);
        }

        return $this->json($form, Response::HTTP_BAD_REQUEST);
    }

    public function deliver(Request $request): Response
    {
        $form = $this->createForm(OrderDeliverRequestTypeForm::class);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $command = new OrderDeliverCommand(
                $data['uuid']
            );

            $this->commandBus->dispatch($command);

            return new JsonResponse(null, Response::HTTP_OK);
        }

        return $this->json($form, Response::HTTP_BAD_REQUEST);
    }

    public function redo(Request $request): Response
    {
        $form = $this->createForm(OrderRedoRequestTypeForm::class);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $newOrderUuid = Uuid::uuid4()->toString();
            $command = new OrderRedoCommand(
                $newOrderUuid,
                $data['uuid']
            );

            $this->commandBus->dispatch($command);

            return new JsonResponse(['uuid' => $newOrderUuid], Response::HTTP_OK);
        }

        return $this->json($form, Response::HTTP_BAD_REQUEST);
    }

    public function cancel(Request $request): Response
    {
        $form = $this->createForm(OrderCancelRequestTypeForm::class);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $command = new OrderCancelCommand(
                $data['uuid']
            );

            $this->commandBus->dispatch($command);

            return new JsonResponse([], Response::HTTP_OK);
        }

        return $this->json($form, Response::HTTP_BAD_REQUEST);
    }
}
