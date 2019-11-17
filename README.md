# Metaforums
An online web-based discussion forum application

## Project Structure
This project is separated between the frontend and the backend application.
- backend/
  The backend of this application is written in PHP, and is API focused. 
    - index.php
      The main handler of backend functions. Handles routing, loading of Mitsumine services, and conversion of array responses to JSON.
    - Mitsumine/
      Mitsumine is a set of custom-written helper classes to consolidate frequently used code.
      - Mitsumine/HTTP
        Mitsumine HTTP contains a number of abstractions for HTTP, such as Request class
      - Mitsumine/Services
        Mitsumine Services contains a number of service classes for common functionality such as Database and Session.
    - Application/
      Application contains classes that are the core of the application itself
      - Controllers/
        Controllers contain controllers that return HTTP responses
- frontend/
  The frontend of this application, written in HTML and utilizes T
- index.php
  This index file allows serving both frontend and backend from one endpoint.
  
## Software Stack

The software is tested on the Apache server and PHP 7.3 on Arch Linux.

## Backend

The database used is MariaDB 10.4.8

## External frontend libraries used

### Vue.js

Vue.js is a progressive, incrementally-adoptable JavaScript framework for building UI on the web

Vue.js allows for interactivity while being less cumbersome than manipulating the DOM manually e.g. with jQuery.

[Project Website](https://vuejs.org)

### jQuery

jQuery is a feature-rich JavaScript library.

Here, jQuery is mainly used for its AJAX functionality.

[Project Website](https://jquery.com)

## CSS Frameworks and Styles used

### Tailwind

Tailwind is a utility-first CSS framework for rapidly building custom designs

[Project Website](https://tailwindcss.com)

## Additional Development
