<!--
---
author: elk
---
-->

![Github repo image](https://i.redd.it/k9g5xxe3bcl21.jpg)

MVC Report
====================

Här nedan följer en manual för min report sida.




Dokumentation
----------------------------

För problem med symphone se - [documentation of the Symfony project](https://symfony.com/doc/current).



Prerequisites
----------------------------

You have installed PHP in the terminal.

You have installed Composer, the PHP package manager.


Clone and get started
----------------------------

1. Clone repository
```bash
# With git and SSH
git clone git@github.com:elk-git/mvc-report.git
```

2. Build files
```bash
# Just to be sure
npm run build
```

3. Start server
```bash
# You are in the app/ directory
symfony server:start
```


Run your app
-----------------------

1. Use Symphony built cli
```bash
# You are in the app/ directory
symfony server:start
```
You can reach it through `http://127.0.0.1:8000`.

Needs to be installed, check dokumentation för symphone.



2. Use php bash

```bash
# You are in the app/ directory
php -S localhost:8888 -t public
```

You should now be able to open a web browser to `http://localhost:8888` and see the welcome page.


Troubleshoot
-----------------------
```
# Show the routes
bin/console debug:router

# Match a specific route
bin/console router:match /lucky/number

# Clear the cache
bin/console cache:clear

# Show available commands
bin/console
```

Linting
-----------------------
```bash
# Be in the app directory.
composer csfix:dry
```


API DOCS
-----------------------

- <b> GET /api/quote </b> Hämta slumpmässigt citat, dagens datum och timestamp.
