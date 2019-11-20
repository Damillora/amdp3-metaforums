# Metaforums
An online web-based discussion forum application


## How to set up

1. Make sure mod_rewrite is enabled on Apache.
2. Create a MySQL / MariaDB database, and import Metaforums' schema (`schema.sql`) into the database.
3. Modify backend/config.php and adjust the configuration as needed.
4. Point the browser to `localhost`

## Sample database data
### Users
1. Username: Administrator
   Password: yuikaadmin
   Role: Site Admin
2. Username: YuikaMitsumine
   Password: producertan
   Role: Idolmaster Shiny Colors Moderator
3. Username: AsahiSerizawa
   Password: asahizzu
   Role: Idolmaster Shiny Colors Moderator
4. Username: YurikoNanao
   Password: toumeinaprologue
   Role: Idolmaster Million Live Moderator
5. Username: JunnaHoshimi
   Password: shakespeare
   Role: Revue Starlight Moderator
6. Username: SuddenVisitor
   Password: omegadim
   Role: User
7. Username: MusubiTendouji
   Password: nanasisters
   Role: User
### Post sources


## Project Structure

- index.php
  The main handler of backend functions. Handles routing, loading of Application services, and response handling.
- Application/
  Application contains classes that are the core of the application itself
  - Assets/
    Assets contains buildable assets, for example source CSS.
  - Controllers/
    Controllers contain controllers that return HTTP responses
  - Foundations/
    Foundations are helper classes for various functions, such as an SQL query builder, and base model implementation;
    - SQLHelper
      Contains SQL escaping facilities.
    - QueryBuilder
      The SQL query builder.
    - Model
      The base model implementation. Contains common code for all models.
  - HTTP
    HTTP contains a number of abstractions for HTTP, such as Request class.
  - Models/
    Models contain database models.
  - Services/
    Services contains a number of service classes for common functionality such as Database and Session.
    - Authentication
      Provide auth related services.
    - Config
      Loads configuration and provides a facility to access the contents.
    - Database
      A service that centralizes database access.
    - Email
      A service for sending email.
    - ServiceContainer
      A service container that ensures every service is loaded once. 
    - Session
      A service that contains centralized session management.
    - View
      Provides view rendering facilities
  - Static/
    Static contains static files served directly by the application. For example, this contains built CSS.
  - Views/
    Views contains all views used by the application

## Software Stack

The software is tested on the Apache server and PHP 7.3 on Arch Linux.

## Backend

The database used is MariaDB 10.4.8

## External frontend libraries used

### jQuery

jQuery is a feature-rich JavaScript library.

Here, jQuery is mainly used for its AJAX functionality.

[Project Website](https://jquery.com)

## CSS Frameworks and Styles used

### Tailwind

Tailwind is a utility-first CSS framework for rapidly building custom designs

[Project Website](https://tailwindcss.com)

## Additional Development
