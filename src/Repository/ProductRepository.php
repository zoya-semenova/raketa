<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Repository;

use Doctrine\DBAL\Connection;
use Raketa\BackendTestTask\Repository\Entity\Product;

class ProductRepository
{
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getByUuid(string $uuid): Product
    {
        $row = $this->connection->fetchOne(
            "SELECT * FROM products WHERE uuid = " . $uuid,
        );

        if (empty($row)) {
            throw new Exception('Product not found');
        }

        return $this->make($row);
    }

    public function getList(array $uuids): array
    {
        $rows = $this->connection->fetch(
            "SELECT * FROM products WHERE uuid IN ( " . implode(",", $uuids) . ")",
        );

        if (empty($rows)) {
            throw new Exception('Products not found');
        }

        $list = [];
        foreach ($rows as $row) {
            $list[] = $this->make($row);
        }

        return $list;
    }

    public function getByCategory(string $category): array
    {
        return array_map(
            static fn (array $row): Product => $this->make($row),
            $this->connection->fetchAllAssociative(
                //здесь кроме id? необходимо сделать селект и других полей
                "SELECT id, uuid, category, is_active, name, description,
       thumbnail, price 
FROM products WHERE is_active = 1 AND category = " . $category,
            )
        );
    }

    public function make(array $row): Product
    {
        return new Product(
            $row['id'],
            $row['uuid'],
            $row['is_active'],
            $row['category'],
            $row['name'],
            $row['description'],
            $row['thumbnail'],
            $row['price'],
        );
    }
}
