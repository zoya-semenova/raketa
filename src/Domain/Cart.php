<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Domain;

final class Cart
{
    private Customer $customer;
    private string $paymentMethod;
    public function __construct(
        readonly private string $uuid,
        private array $items,
    ) {
        //думаю здесь необходимо получить customer и payment_method
        //вероятно они хранятся в сессии
        $this->customer = new Customer($_SESSION['id'], $_SESSION['firstName'],
            $_SESSION['lastName'], $_SESSION['middleName'], $_SESSION['email']);
        $this->paymentMethod = $_SESSION['paymentMethod'];
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function getPaymentMethod(): string
    {
        return $this->paymentMethod;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function addItem(string $key, CartItem $item): void
    {
        //здесь вынесла бы uuid товара в качестве ключа массива итемов
        //далее обновляем параметры итема через сложение массивов
        $this->items[$key] = $item + $this->items[$key];
    }
}
