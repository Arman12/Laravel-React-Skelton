# Multi-Step Form with React, Laravel, and MySQL


## Table of Contents

- [Introduction](#introduction)
- [TechStack](#TechStack)
- [Prerequisites](#Prerequisites)
- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [Contributing](#contributing)
- [License](#license)

## Introduction

This project showcases a multi-step form implementation using React for the frontend, Laravel for the backend, and MySQL for data storage. The multi-step form is a common UI pattern used to break down complex data input processes into manageable steps.

## TechStack

- Frontend: React
- Backend: Laravel (for the RESTful API)
- Database: MySQL

## Preruisites
- php: 8.2
- NodeJS: 18.14.2
## Features

- Multi-Step Form: The project demonstrates how to create a multi-step form with React components for each step.
- Backend API: Utilizes Laravel to build a RESTful API for handling form submissions and data storage.
- MySQL Database: The submitted form data is stored in a MySQL database for persistence.
- Step Validation: Each step may have its own validation logic, ensuring data integrity.
- User-Friendly Interface: The React frontend provides an intuitive and user-friendly form interaction.

## Installation

1- Clone the repository: git clone https://github.com/Arman12/Laravel-React-Skelton.git

2- Install frontend dependencies: npm install in root folder

3- Install backend dependencies: composer install in root folder
Set up your MySQL database and update .env files in root directory with appropriate configuration.

4- Run migrations to set up database tables: php artisan migrate

5- Start the frontend development server: npm start

6- Start the backend server: php artisan serve

## Usage

1- Access the frontend application at [http://localhost:3000](http://localhost:3000).

2- Follow the steps of the multi-step form to input data.

3- Submit the form to the backend API, which will store the data in the MySQL database.

## Contributing
Contributions are welcome! Feel free to submit pull requests or open issues.

## License
This project is licensed under the [MIT License](https://opensource.org/license/mit/).



