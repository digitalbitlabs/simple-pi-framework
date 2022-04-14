<p align="center"><img src="https://user-images.githubusercontent.com/72734777/127652853-7592fc23-95d0-4dc7-9a07-ddeeaf85f3d9.png"/></p>

> This repository contains the source code of Simple Pi framework. To create a project or app using the framework visit [https://github.com/digitalbitlabs/simple-pi](https://github.com/digitalbitlabs/simple-pi) 

# Simple Pi 
Simple Pi is a REST API micro framework developed in PHP. The sole purpose of this framework is give you ready to use simple API which allows you to have custom configuration, routes and database operations. To begin with using the framework, follow the steps given below:


## Installation
To install Simple Pi, you need to have composer installed. If you don't grab it from [https://getcomposer.org/download/](https://getcomposer.org/download/)

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
Simple Pi uses Capsule Manager by Laravel for carrying out database operations. For more information visit https://laravel.com/api/8.x/Illuminate/Database/Capsule/Manager.html.

As per the official documentation from Laravel following drivers are supported by Capsule Manager.

* MariaDB 10.2+ 
* MySQL 5.7+
* PostgreSQL 10.0+
* SQLite 3.8.8+
* SQL Server 2017+

To perform a query to select data simply add the lines at the top of your controller or routes.php file

`use SimplePi\Framework\DB;`

Then to fetch the records from any table simply use the ORM functions provided by Laravel. Visit https://laravel.com/docs/9.x/database for more information.

`DB::table('demo')->get()->toArray();`

That's it. You get an array of your database table.

>*To perform additional operations like delete or update or insert, use the respective functions from `CapsuleManager` class. The functions can be executed in a similar fashion as Laravel eloquent. You can refer to Laravel eloquent documentation for more details.*


## Credits
This framework is developed by [Sanket Raut](https://twitter.com/sanketmraut) as a hobby project at [Digitalbit Labs](https://digitalbit.in) to write a bare metal framework that can be kept as simple as possible.
