# mvc2025

## Intro  
This is a repo for the BTH course Objektorienterade webbteknologier, a.k.a. mvc.  
This PHP website is built in the framework symfony. The main objective of the course is to get a deeper understanding of object oriented programming techniques in PHP along with databases (Object Relational Mapping - ORM) and unit testing and documentation (PHPUnit tests, phpDocumentor...)
  
## Getting started
Go to a directory of your choice, for example 'app/'.
<pre>
git clone https://github.com/studAnja22/mvc2024
  
composer install
</pre>

## Run the app
<pre>
You are in the directory you chose for this app, for example 'app/'.  
  
php -S localhost:8888 -t public
</pre>
  
Now you can access the website on: **localhost:8888**

### PHPUnit test and phpDocumentor
<pre>
You are in the directory you chose for this app, for example 'app/'.  
php -S localhost:8889
</pre>
  
- Check PHPUnit test coverage here (which is currently at *100%*!)  
  
(http://localhost:8889/build/coverage/index.html)
  
- Check documentations here  
  
(http://localhost:8889/docs/api/)  

## Scripts

- composer phpunit
- composer phpdoc
- composer validate
- composer lint
- composer csfix
- composer clean

### To update CSS style:
- npm run dev

## Requirements
- PHP >=8.2
- Composer
- Symfony 7.2.*
- PHPUnit ^11

## Development
The app is under development and has currently started kmom05 where we will add support for the database and ORM.