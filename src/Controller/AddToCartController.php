<?php

namespace Raketa\BackendTestTask\Controller;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Raketa\BackendTestTask\Domain\CartItem;
use Raketa\BackendTestTask\Domain\Customer;
use Raketa\BackendTestTask\Repository\CartManager;
use Raketa\BackendTestTask\Repository\ProductRepository;
use Raketa\BackendTestTask\View\CartView;
use Ramsey\Uuid\Uuid;

readonly class AddToCartController
{
    public function __construct(
        private ProductRepository $productRepository,
        private CartView $cartView,
        private CartManager $cartManager,
    ) {
    }

    public function get(RequestInterface $request): ResponseInterface
    {
        $rawRequest = json_decode($request->getBody()->getContents(), true);
        $product = $this->productRepository->getByUuid($rawRequest['productUuid']);

        //забыли про customer
        $cart = $this->cartManager->getCart();
        $cart->addItem(
         //я бы не стала задавать итемам uuid,
        // т.к. не понимаю его предназначения в данном случае
        //а product uid вынести первым параметром и использовать как ключ для массива с параметрами
            $product->getUuid(),

            new CartItem(
                $product->getPrice(),
                $rawRequest['quantity'],
            )
        );

        //забыли сохранить
        $this->cartManager->saveCart($cart);

        $response = new JsonResponse();
        $response->getBody()->write(
            json_encode(
                [
                    'status' => 'success',
                    'cart' => $this->cartView->toArray($cart)
                ],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            )
        );

        return $response
            ->withHeader('Content-Type', 'application/json; charset=utf-8')
            ->withStatus(200);
    }
}
