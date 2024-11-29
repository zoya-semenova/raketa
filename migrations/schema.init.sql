create table if not exists products
(
    id int auto_increment primary key,
    /* уникальный индентификатор должен иметь свойство unique */
    uuid  varchar(255) not null unique comment 'UUID товара',
    category  varchar(255) not null comment 'Категория товара',
    is_active tinyint default 1  not null comment 'Флаг активности',
    name text default '' not null comment 'Тип услуги',
    description text null comment 'Описание товара',
    thumbnail  varchar(255) null comment 'Ссылка на картинку',
    /*для цены я бы использовала decimal а не float*/
    price decimal not null comment 'Цена'
)
    comment 'Товары';

create index is_active_idx on products (is_active);
