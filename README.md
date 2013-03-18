# MyDAO

Minha estrutura de classes para abstração de banco de dados


Instânciando o objeto da query
-------------

```php
<?php use Respect\Relational\Mapper;
      $mapper = new Mapper(new PDO('sqlite:database.sq3'));
```