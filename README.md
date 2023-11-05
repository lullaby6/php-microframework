# PHP Micro Framework

## Start the Server

```bash
php -S 127.0.0.1:3000
```

## Routing

All http requests are redirected to ```index.php``` by ```.htaccess```, then the router takes care of routing the request with the file specified in the router ("./pages").

By default the router only redirects to the index.php of the request url, but you can specify it to redirect to any file within the specified path by adding a false when calling the function.

```php
router('./pages', false)
```

## Environment Variables

You can access the environment variables of the file ```".env"``` by calling the file ```"env.php"``` in ```"utilities"``` directory, the environment variables will be stored in ```$_ENV```

.env file:

```yml
APP_NAME="MY_APP_NAME"
```

php example:

```php
include_once ROOT_PATH . "/utilities/env.php";

echo $_ENV['APP_NAME'];
```

## Database

This is a simple Database class for performing common database operations using PHP and PDO. The class provides methods for database connection, creating tables, dropping tables, executing SQL queries, and performing CRUD (Create, Read, Update, Delete) operations on data.

### How to Use

### 1. Initialize the Database

First, create an instance of the `Database` class by providing the necessary database connection details:

```php
$db = new Database('mysql', 'localhost', 'my_database', 'username', 'password');
```

### 2. Creating Tables

To create a table, you can use the `users_table` method. Provide the table name and an array of column definitions:

```php
$users_table = [
    'name' => 'users',
    'columns' => [
        'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
        'name' => 'VARCHAR(50)',
        'email' => 'VARCHAR(100)'
    ]
];
$result = $db->create_table($users_table['name'], $users_table['columns']);
```

### 3. Deleting Tables

You can delete a table if it exists using the `delete_table` method by providing the table name:

```php
$result = $db->delete_table('users');
```

### 4. Running SQL Queries

You can execute custom SQL queries using the `query` method. It returns the result set if the query is successful:

```php
$sql = "SELECT * FROM users";
$users = $db->query($sql);
```

### 5. Running Parametrized Queries and Prevent SQL Injection

You can execute parametrized SQL queries using the `execute` method. It takes the SQL query and an array of parameters as inputs:

```php
$sql = 'SELECT * FROM users WHERE id > :id';
$users = $db->execute($sql, [':id' => 1]);
```

### 6. Selecting Data

You can retrieve data from a table using the `select` method. Provide the table name and optional conditions:

```php
$conditions = ['id' => 1];
$users = $db->select('users', $conditions);
```

### 7. Inserting Data

To insert a new record, use the `create` method with the table name and an array of data:

```php
$user = ['name' => 'Lullaby', 'email' => 'lucianobrumer5@gmail.com'];
$new_user_id = $db->create('users', $user);
```

### 8. Updating Data

You can update data in a table using the `update` method. Provide the table name, data to update, and optional conditions:

```php
$data = ['email' => 'lucianobrumer5@gmail.com2'];
$conditions = ['id' => 1];
$user_updated = $db->update('users', $data, $conditions);
```

### 9. Deleting Data

To delete records, use the `delete` method with the table name and conditions:

```php
$user_deleted = $db->delete('users', ['id' => 2]);
```

### 10. Closing the Database Connection

Don't forget to close the database connection when you're done:

```php
$db->close();
```