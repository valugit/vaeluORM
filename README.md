# VaeluORM

A little ORM, for MySQL only.

## Initialization
------

First, you will have to install all required dependencies :

`$composer install`

Then, go to the `config` folder and rename the `config.yaml.dist` to `config.yaml` and complete it with your own configuration. Don't forget to create your database. 

You can now start configuring the ORM. In your project, create a file `init.php` and add the following :
```php
<?php

use VaeluORM\Manager;
use Symfony\Component\Yaml\Yaml;

require_once "vendor/autoload.php";

$config = Yaml::parseFile(__DIR__.'/../config/config.yaml');

$dsn = "mysql:host=" . $config['database']['host'].";port=" . $config['database']['port'].";dbname=" . $config['database']['name'];
$user = $config['database']['user'];
$pwd = $config['database']['password'];
$conn = new PDO($dsn, $user, $pwd);

$manager = new Manager($conn, $config['database']['name']);
```

And you are now ready to start using the ORM.

## Documentation
------

### Entities

To start using the ORM, you will have to create entities.
All your entities must inherit from ```VaeluORM\BaseEntity``` and must have a three protected variables :
* `$entityName`, that will contain the entity name as String
* `$table`, that will contain the table name for your entity as String
* `$columns`, that will contain an associative array in which the keys are the columns name and the values are the content type

You can see a complete example here :
```php
<?php

namespace App;

use VaeluORM\BaseEntity;

class BubbleTea extends BaseEntity
{
    protected $entityName;
    protected $table;
    protected $columns;

    public function __construct($connection="")
    {
        $this->entityName = 'BubbleTea';
        $this->table = 'bubble_tea';
        $this->columns = [
            'tea'=>'str',
            'flavor'=>'str',
            'poppings'=>'str',
            'size'=>'int',
            'hot'=>'bool'
        ];
        parent::__construct($connection);
    }
}
```

### Create

Create a new instance of you entity, then use the function `set($column, $value)` with two parameters, the first one will be your column name and the second one the value to attribute.
If you want to add multiple columns, use the function multiple times. and then use the function `save($entity)` with your new entity as parameter to add your entity to the database.

For example :
```php
<?php

require_once('init.php');

use App\BubbleTea;

$TeaRepo = $manager->getEntity('BubbleTea');

$tea = new BubbleTea();

$tea->set('tea', 'green');
$tea->set('flavor', 'rose');
$tea->set('poppings', 'lemon');
$tea->set('size', 500);
$tea->set('hot', 0);

$TeaRepo->save($tea);
echo "New bubble tea created : " . $tea->getId() . "\n";
```

### Read

#### getOneBy($column, $value)
You can get a single entity by any of it's characteristics by using `getOneBy($column, $value)`

Example :
```php
<?php

require_once('init.php');

use App\BubbleTea;

$TeaRepo = $manager->getEntity('BubbleTea');

$idTea = $TeaRepo->getOneBy('id', '1');
```

#### getAll($optional = [])

You can get all the all the entities of your database using `getAll()`.
This function take an optional parameter as an associative array that will permit you to add limit or order to your query.

Example :
```php
<?php

require_once('init.php');

use App\BubbleTea;

$TeaRepo = $manager->getEntity('BubbleTea');

$allTeas = $TeaRepo->getAll();

$fourTeas = $TeaRepo->getAll(["limit" => 4, "orderby" => "flavor", "order" => "DESC"]);
```

#### getAllBy($optional = [])

The `getAllBy()` function is pretty much the same as the `getAll()` function, except you can get all the entities that have a specific characteristic.

Exemple :
```php
<?php

require_once('init.php');

use App\BubbleTea;

$TeaRepo = $manager->getEntity('BubbleTea');

$greenTeas = $TeaRepo->getAllBy(["where" => ["tea" => "green"], "limit" => 2, "orderby" => "flavor"]);
```

#### getId()

You can get the id of an entity by simply using a `getId()`.

Exemple :
```php
$tea->getId();
```

#### __get()

You can get any of the attributes of you entity by simply using a `->your_attribute`.

Exemple :
```php
$tea->flavor;
```

### Update

To update an entity, this is pretty much the same as for create a new one.
Use `set($column, $value)` and then `save($entity)` but this time add a second parameter that will be the id of the entity that you want to update.

Example :
```php
<?php

require_once('init.php');

use App\BubbleTea;

$TeaRepo = $manager->getEntity('BubbleTea');

$tea = new BubbleTea();

$tea->set('tea', 'green');
$tea->set('flavor', 'kiwi');
$tea->set('poppings', 'tapioca');
$tea->set('size', 700);
$tea->set('hot', 1);

$TeaRepo->save($tea, 5);
echo "Bubble tea successfully updated : " . $tea->getId() . "\n";
```

### Delete

To delete an entity simply use `delete($id)`.

Example :
```php
<?php

require_once('init.php');

use App\BubbleTea;

$TeaRepo = $manager->getEntity('BubbleTea');

$TeaRepo->delete(1);
```

### Other functions

#### count($optional = [])

This function allows you to know how many items you have in your table. You can add optionals parameters to to know, for example, how many entities have a specific characteristic or add a limit.

Example :
```php
$blackTeaQuantitea = $TeaRepo->count(["where" => ["tea" => "black"]]); // return an integer
```

#### exists($optional = [])

This function allows you to check if a specific data or set of data exists in your database.

Example:
```php
$lemonTeas = $TeaRepo->exists(["flavor" => "lemon"]) // return True or False
```

And there you have it all : a little ORM without pretention...