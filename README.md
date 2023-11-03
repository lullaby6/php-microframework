# Routing

All http requests are redirected to ```index.php``` by ```.htaccess```, then the router takes care of routing the request with the file specified in the router ("./pages").

By default the router only redirects to the index.php of the request url, but you can specify it to redirect to any file within the specified path by adding a false when calling the function.

```php
router('./pages', false)
```

# Database

This is a simple Object-Relational Mapping (ORM) class for performing common database operations using PHP and PDO. The class provides methods for database connection, creating tables, dropping tables, executing SQL queries, and performing CRUD (Create, Read, Update, Delete) operations on data.

## How to Use

### 1. Initialize the Database

First, create an instance of the `Database` class by providing the necessary database connection details:

```php
$db = new Database('mysql', 'localhost', 'my_database', 'username', 'password');
```