# PHP Boilerplate for prototyping

I wrote this very simple application to easily create data models to interact with. The data structure is created via a configuration object and creates a query interface that returns JSON.

### What this does
 - Creates database and tables via configuration
 - Provides a query interface for every model

### How to query the database:
You can query the database via $_GET parameters. All queries return JSON.

#### Index
https://{url}?{model-name}&all

#### Create
https://{url}?{model-name}&create&key=value&key=value

#### Read
https://{url}?{model-name}&id=1

#### Update
https://{url}?{model-name}&update&key=value&key=value

#### Delete
https://{url}?{model-name}&delete&id=1

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
- Query Hooks (before/after)
- Convert $app_config to YAML? Maybe.

## Contributing

I would like to keep this as a simple backend prototyping tool that I can use along side a frontend framework like React.

### Things I need help with:
1. Query building
2. Database/Table Migration (changed to config object should change the actual database tables)
3. Query Hooks (before/after)
4. Table joins
5. Everything else...

If you have an suggestions or ideas, I am all ears: Put in a pull request, comment on my shameful code, or fork this baby!
