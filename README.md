# ToDoList

It is the 8th project of my studies. - 
Project made with Symfony 3.4.

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/19191399262547fd995560244ca3c198)](https://www.codacy.com/manual/MaxiKata/projet8-TodoList?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=MaxiKata/projet8-TodoList&amp;utm_campaign=Badge_Grade)
![OenClassRooms](https://img.shields.io/badge/OpenClassRooms-DA_PHP/SF-blue.svg)
![Project](https://img.shields.io/badge/Project-8-blue.svg) 
![PHP](https://img.shields.io/badge/Symfony-3.4-blue.svg)

## Installation
### 1 - Install Composer
**=>** [https://getcomposer.org/download/](https://getcomposer.org/download/)

### 2 - Install the project
1 - Clone or download the GitHub repository in the desired folder:
```
    git clone https://github.com/MaxiKata/projet8-TodoList.git
```
2 - Install libraries by running : 
```
    composer install
```

### 3 - Modify Databases Connection

1 - Database of the application
**=>** Modify file, app/config/parameters.yml :
```
  database_host: localhost
  database_port: 8000
  database_name: projet8-TodoList
  database_user: root
  database_password: password
```

2 - Database of the TEST for the application
**=>** Modify file, app/config/parameters_test.yml :
```
  database_host: localhost
  database_port: 8000
  database_name: projet8-TodoList-test
  database_user: root
  database_password: password
```

### 4 - Install Databases

1 - Command to create & launch database of the application:
```
    php bin/console doctrine:database:create
    php bin/console doctrine:schema:update --force
```

2 - Command to create for database of the TEST for the application:
```
    php bin/console doctrine:database:create --env=test
    php bin/console doctrine:schema:update --env=test
```

### 5 - Launch your server

1 - Command to launch:

```
    php bin:console server:run
```
Or
```
    symfony server:start
```

## License

[MIT](https://github.com/MaxiKata/projet8-TodoList/blob/master/LICENSE.md)

![Permissions](https://img.shields.io/badge/Permissions-Commercial_use-green.svg) 
![Permissions](https://img.shields.io/badge/Permissions-Distribution-green.svg) 
![Permissions](https://img.shields.io/badge/Permissions-Modification-green.svg) 
![Permissions](https://img.shields.io/badge/Permissions-Private_use-green.svg)

![Conditions](https://img.shields.io/badge/Conditions-License_and_copyright_notice-blue.svg)

![Limitations](https://img.shields.io/badge/Limitations-Liability-red.svg)
![Limitations](https://img.shields.io/badge/Limitations-Warranty-red.svg)