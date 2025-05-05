<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class Cart
{
    private array $items = [];

    public function addItem(Livres $book): void
    {
        $this->items[] = $book;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function clear(): void
    {
        $this->items = [];
    }
}
