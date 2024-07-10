# PHP Micro Framework

## Start the Server

```bash
php -S 127.0.0.1:3000
```

## Context

```php
$_ENV; // environment variables

$_BODY; // request body

$_HEADERS; // request headers

$_FULL_URL;

$_URL;

$_PATH; // $_URL path

$_QUERY_STRING; // url query string

$_PATH_VALUE;

$_IP; // client request ip
```

## Routing

All http requests are redirected to by the ```.htaccess``` file, then the router takes care of routing the request with the ```router``` function.

```php
router();
```

## Environment Variables

You can access the environment variables of the file ```".env"``` by calling the file ```"env.php"``` in ```"utils"``` directory, the environment variables will be stored in ```$_ENV```

```.env``` file:

```yml
APP_NAME=MY_APP_NAME
```

php example:

```php
include_once ROOT_PATH . "/utils/env.php";

echo $_ENV['APP_NAME'];
```

## Database

This is a simple Database class for performing common database operations using PHP and PDO. The class provides methods for database connection, creating tables, dropping tables, executing SQL queries, and performing CRUD (Create, Read, Update, Delete) operations on data.

### Initialize the Database

First, create an instance of the `Database` class by providing the necessary database connection details:

```php
$db = new Database([
    'driver' => 'mysql',
    'database' => 'my_database',
    'host' => 'localhost',
    'username' => 'root',
    'password' => '123456',
]);
```

### Creating Tables

To create a table, you can use the `users_table` method. Provide the table name and an array of column definitions:

```php
$result = $db->create_table('users', [
    'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
    'username' => 'VARCHAR(50)',
    'email' => 'VARCHAR(100)'
]);
```

### Deleting Tables

You can delete a table if it exists using the `delete_table` method by providing the table name:

```php
$result = $db->delete_table('users');
```

### Getting Table Columns

To get columns name/field, type, default and etc... you can use `get_table_columns` method by providing the table name:

```php
$users_table_columns = $db->get_table_columns('users')
```

### Running SQL Queries

You can execute custom SQL queries using the `query` method. It returns the result set if the query is successful:

```php
$sql = "SELECT * FROM users";
$users = $db->query($sql);
```

### Running Parametrized Queries and Prevent SQL Injection

You can execute parametrized SQL queries using the `execute` method. It takes the SQL query and an array of parameters as inputs:

```php
$sql = 'SELECT * FROM users WHERE id > :id';
$users = $db->execute($sql, [':id' => 1]);
```

### Selecting Data

You can retrieve data from a table using the `select` method. Provide the table name and optional conditions:

```php
$users = $db->select('users', [
    'columns' => 'id, username', // default: *
    'where' => [
        ['id', '=', 1],
        ['username', 'LIKE', 'a%'],
    ],
    'order_by' => 'id DESC, username ASC',
    'group_by' => 'username',
    'limit' => 5
]);
```

#### Using joins

```php
$users = $db->select('users', [
    'where' => [
        ['users.id', '>', 1],
        ['staff.id', '>', 1],
    ],
    'join' => [
        [
            'table_name' => 'staff',
            'type' => 'RIGHT', // default: INNER
            'on' => 'users.id = staff.user_id'
        ],
        [
            'table_name' => 'purchases',
            'columns' => 'id, product_id', // default: *
            'on' => 'users.id = purchases.user_id'
        ],
        [
            'table_name' => 'products',
            'columns': => 'name', // default: *
            'on' => 'purchases.product_id = products.id'
        ]
    ],
]);
```

### Inserting Data

To insert a new record, use the `create` method with the table name and an array of data:

```php
$user_id = $db->create('users', [
    'username' => 'Lullaby',
    'email' => 'lullaby2023@gmail.com'
]);
```

### Updating Data

You can update data in a table using the `update` method. Provide the table name, data to update, and optional conditions:

```php
$result = $db->update('users', [
    'email' => 'lullaby2024@gmail.com'
],[
    ['id', '=', 1]
]);
```

### Deleting Data

To delete records, use the `delete` method with the table name and conditions:

```php
$result = $db->delete('users', [
    ['id', '>', 1]
]);
```

### Closing the Database Connection

Don't forget to close the database connection when you're done:

```php
$db->close();
```

## Transactions

When you use a the ```transacion``` method a ```START TRANSACTION``` is started, but the callback function from ```transaction``` method throws an error, the transaction run a ```ROLLBACK```, but if no one error, the transaction run a ```COMMIT```.

```php
$db->transaction(function() {
    $user_id = $db->create('users', [
        'username' => 'Lullaby',
        'email' => 'lullaby2023@gmail.com'
    ]);

    $db->update('users', [
        'email' => 'lullaby2024@gmail.com'
    ],[
        ['id', '=', $user_id]
    ]);

    $db->delete('users', [
        ['id', '=', $user_id]
    ]);

    return $user_id;
})

```