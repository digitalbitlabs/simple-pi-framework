# Simple Pi
> This repository contains the source code of Simple Pi framework. To create a project or app using the framework visit [https://github.com/digitalbitlabs/simple-pi](https://github.com/digitalbitlabs/simple-pi) 
 
Simple Pi is a REST API micro framework developed in PHP. The sole purpose of this framework is give you ready to use simple API which allows you to have custom configuration, routes and database operations. To begin with using the framework, follow the steps given below:

## Installation
To install Simple Pi, you need to have composer installed. If you dont grab it from [https://getcomposer.org/download/](https://getcomposer.org/download/)

`composer require digitalbitlabs/simple-pi my-app`

This will create a directory `my-app` in your current folder with all the code necessary to run the API.

## Setup env file
Enter `my-app` folder and rename the `.env.example file` to `.env`

## Configure database and application
Customise the configuration parameters in `.env` file accordingly. You can also update the settings in `config.php` file placed inside `app` folder in the root directory of your application.

## Setup application routes
Change or add app routes through `routes.php` file inside app folder in the root directory of your application.

## Run the application
To run the app you just created run the following in your terminal from the root folder of your app.

`php -S localhost:8000 -t public\`

## Controllers
Controllers are custom classes to group similar operations together. They can be created inside `app\controllers` classes. A Demo controller is already present in the repository code.

## Database operations
Simple Pi uses PHP PDO objects to run database queries. Following drivers are supported as per the php.net documentation.

* CUBRID (PDO)
* MS SQL Server (PDO)
* Firebird (PDO)
* IBM (PDO)
* Informix (PDO)
* MySQL (PDO)
* MS SQL Server (PDO)
* Oracle (PDO)
* ODBC and DB2 (PDO)
* PostgreSQL (PDO)
* SQLite (PDO) 

To perform a query simply add the lines at the top of your controller or routes.php file

`use SimplePi\Framework\DB;`

Then run the query and fetch results using DB::query()->result() function.

`DB::query("SELECT * FROM foo")->result();`

That's it. You get an array of your database table.

## Credits
This framework is developed by [Sanket Raut](https://twitter.com/sanketmraut) as a hobby project at [Digitalbit Labs](https://digitalbit.in) to write a bare metal framework that can kept as simple as possible.