<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\View;

use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Repository\ProductRepository;

readonly class CartView
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    public function toArray(Cart $cart): array
    {
        $data = [
            'uuid' => $cart->getUuid(),
            'customer' => [
                'id' => $cart->getCustomer()->getId(),
                'name' => implode(' ', [
                    $cart->getCustomer()->getLastName(),
                    $cart->getCustomer()->getFirstName(),
                    $cart->getCustomer()->getMiddleName(),
                ]),
                'email' => $cart->getCustomer()->getEmail(),
            ],
            'payment_method' => $cart->getPaymentMethod(),
        ];

        $total = 0;
        $data['items'] = [];
        $productIds = array_keys($cart->getItems());
        $products = $this->productRepository->getList($productIds);
        foreach ($cart->getItems() as $key => $item) {
            $total += $item->getPrice() * $item->getQuantity();
            //смутил запрос в цикле, предложила бы вынести
            //$product = $this->productRepository->getByUuid($item->getProductUuid());
            $product = $products[$key];
            $data['items'][] = [
                'uuid' => $item->getUuid(),
                'price' => $item->getPrice(),
                //тогда вероятно здесь тотал не нужен
                //'total' => $total,
                'quantity' => $item->getQuantity(),
                'product' => [
                    'id' => $product->getId(),
                    'uuid' => $product->getUuid(),
                    'name' => $product->getName(),
                    'thumbnail' => $product->getThumbnail(),
                    //тоже дублирует инф-цию
                    //'price' => $product->getPrice(),
                ],
            ];
        }

        $data['total'] = $total;

        return $data;
    }
}
