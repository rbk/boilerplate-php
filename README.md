# PHP Boilerplate for prototyping

I wrote this very simple application to easily create data models to interact with. The data structure is created via a configuration object and creates a query interface that returns JSON.

### What this does
 - Creates database and tables via configuration
 - Provides a query interface for every model

### Goals
 - Quickly start prototyping
 - Automatically have CRUD based on configuration

### Setup

1. Start a LAMP/MAMP server
2. Put files in public directory
3. Edit configuration in index.php

### Sample app_config
```
$app_config = array(
  'database' => array(
    'host' => 'localhost',
    'user' => 'root',
    'password' => 'root',
    'database' => 'boilerplate_php'
  ),
  'models' => array(
    array(
      'name' => 'quotes',
      'columns' => [
        'quote' => 'varchar(255)',
      ]
    ),
    array(
      'name' => 'users',
      'columns' => [
        'name' => 'varchar(255)',
        'email' => 'varchar(255)',
      ]
    ),
  )
);
```

### Todo
- Create composer package
- Abstract database interaction
- Create database migration functions
- Implement logging
- Add testing

## Contributing

I would like to keep this as a simple backend prototyping tool that I can use along side a frontend framework like React.

### Things I need help with:
1. Query building
2. Database/Table Migration (changed to config object should change the actual database tables)

If you have an suggestions or ideas, I am all ears: Put in a pull request, comment on my shameful code, or fork this baby!
