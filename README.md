# PHP Boilerplate (prototyping tool)

### About

I wrote this very simple application to easily create data models to interact with (Think RoR or Django). The data structure is created via a configuration object and creates a query interface that returns JSON objects. ***The cool part is that the application only has one endpoint.***

### What this does
1. Creates database and tables via configuration
2. Provides a query interface for every model

### How to query the database:
You can query the database via GET/POST parameters, and by sending a JSON string. All queries return JSON.


#### Via Ajax:
Payload:<br>
Index
```
{
  model: 'todos',
  all: true
}
```
Create
```
{
  model: 'todos',
  create: true,
  myfield: 'myvalue',
  mycolumn: 'mycolumnvalue'
}
```
Update
```
{
  id: '<item-id>'
  model: 'todos',
  update: true,
  myfield: 'myNEWvalue',
  mycolumn: 'myNEWcolumnvalue'
}
```

etc...

#### Via URL String:

Index<br>
`https://{url}?{model-name}&all`

Create<br> `https://{url}?{model-name}&create&key=value&key=value`

Read<br>
`https://{url}?{model-name}&id=1`

Update<br>
`https://{url}?{model-name}&update&key=value&key=value`

Delete<br>
`https://{url}?{model-name}&delete&id=1`

#### Via Post:
Sample post from form:
```
id: <id>,
update: true,
key: value
```

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

### Done
- Break down request content type in request handler. Allow requests to be made via multipart form, ajax, and get.

## Contributing

I would like to keep this as a simple backend prototyping tool that I can use along side a frontend framework like React.

### Things I need help with:
1. Query building
2. Database/Table Migration (changed to config object should change the actual database tables)
3. Query Hooks (before/after)
4. Table joins
5. Everything else...

If you have an suggestions or ideas, I am all ears: Put in a pull request, comment on my shameful code, or fork this baby!
