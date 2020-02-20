<?php

declare(strict_types=1);

namespace App\UI\HTTP\REST\DTO;

final class OrderRequestDto
{
    private array $establishment;
    private array $catalogFlow;
    private string $tableIdentifier;
    private array $items;

    public function __construct()
    {
        $this->tableIdentifier = '';
        $this->establishment = [];
        $this->catalogFlow = [];
        $this->items = [];
    }

    public function getEstablishment(): array
    {
        return $this->establishment;
    }

    public function setEstablishment(array $establishment): void
    {
        $this->establishment = $establishment;
    }

    public function getCatalogFlow(): array
    {
        return $this->catalogFlow;
    }

    public function setCatalogFlow(array $catalogFlow): void
    {
        $this->catalogFlow = $catalogFlow;
    }

    public function getTableIdentifier(): string
    {
        return $this->tableIdentifier;
    }

    public function setTableIdentifier(string $tableIdentifier): void
    {
        $this->tableIdentifier = $tableIdentifier;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function setItems(array $items): void
    {
        $this->items = $items;
    }
}
