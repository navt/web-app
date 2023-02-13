# Simple Blog in Dart+PHP
This study was carried out with the aim of:
- acquire skills in programming on Dart (client side) and,
- write api applications in PHP (server side).
The work was carried out with the knowledge base that is currently available, therefore some solutions are far from ideal. I can't believe that you can read a book and then immediately do everything right. Before reading anything I need some practical background.
Of the technical points, it should be noted that JWT was used for authentication, and IndexedDB was used to store the token on the client.

The application was deployed on the local computer(OS from the Ubuntu family). Required:
- Dart SDK,
- PHP,
- MySQL or MariaDB.

## How to deploy an application
Clone this repository to your computer
```bash
$ git clone https://github.com/navt/web-app.git
```
Create a MySQL database, import the dump `/web-app/web/data/db.sql` into your database.
Adjust `/web-app/web/php-lib/values.ini` according to your values.
There are no ready-made JS files in the repository, they will need to be compiled.
```bash
$ cd web-app
$ dart compile js -O1 -o web/js/add.dart.js web/bin/add.dart
```
You need to compile all files from the `/web-app/web/bin` directory.
Start the PHP Embedded Web Server
```bash
$ cd web-app
$ cd web
$ php -S localhost:8000
```
In a browser go to `http://localhost:8000`
To enter, use a pair of login / password: `demo@demo.ru / qwerty`.
