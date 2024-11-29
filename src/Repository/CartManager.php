<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Repository;

use Exception;
use Psr\Log\LoggerInterface;
use Raketa\BackendTestTask\Domain\Cart;
use Raketa\BackendTestTask\Domain\Customer;
use Raketa\BackendTestTask\Infrastructure\ConnectorFacade;

class CartManager extends ConnectorFacade
{
    public $logger;

    public function __construct($host, $port, $password)
    {
        parent::__construct($host, $port, $password, 1);
        parent::build();
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function saveCart(Cart $cart)
    {
        try {
            $this->connector->set(session_id(), $cart);
        } catch (Exception $e) {
            $this->logger->error('Error');
        }
    }

    /**
     * @return ?Cart
     */
    public function getCart()
    {
        try {
            //вероятно здесь следует возвращать объект Cart
            return new Cart(session_id(), $this->connector->get(session_id()),
                );
        } catch (Exception $e) {
            $this->logger->error('Error');
        }

        return new Cart(session_id(), []);
    }
}
