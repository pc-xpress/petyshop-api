# PetSocial - A Social Network for Pets

Welcome to PetSocial, the ultimate social network for pets! This application allows users to create profiles for their pets, share posts, make friends, and more. Built with Laravel 11, this project is designed to provide a seamless and fun experience for pet owners and their furry friends.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Usage](#usage)
- [API Endpoints](#api-endpoints)
- [Database Structure](#database-structure)
- [Running Tests](#running-tests)
- [Contributing](#contributing)
- [License](#license)

## Features

- User authentication and profile management
- Create and manage pet profiles
- Post updates and share media
- Comment and react to posts
- Friend requests and messaging
- Paginated lists of posts and pets

## Installation

To get started with PetSocial, follow these steps:

1. **Clone the repository:**
```bash
   git clone https://github.com/pc-xpress/petyshop-api.git

   cd petyshop-api
```

2. **Install dependencies:**
```bash
composer install
npm install
```

3. **Copy the example environment file and configure your environment variables:**
```bash
cp .env.example .env
```

4. **Generate an application key:**
```
php artisan key:generate
```

5. **Run the database migrations and seed the database:**
```
php artisan migrate --seed
```

6. **Start the local development server:**
```
php artisan serve
```
## Usage

Once the server is running, you can access the application at `http://localhost:8000.`

## API Endpoints
Here are some of the key API endpoints:

  ### Authentication:

  - `POST /api/v1/register` - Register a new user
  - `POST /api/v1/login` - Login a user

  ### Pets:

  - `GET /api/v1/pets` - List all pets (paginated)
  - `POST /api/v1/pets` - Create a new pet
  - `GET /api/v1/pets/{id}` - Get a specific pet
  - `PUT /api/v1/pets/{id}` - Update a pet
  - `DELETE /api/v1/pets/{id}` - Delete a pet

### Posts:

- `GET /api/v1/posts` - List all posts (paginated)
- `POST /api/v1/posts` - Create a new post
- `GET /api/v1/posts/{id}` - Get a specific post
- `PUT /api/v1/posts/{id}` - Update a post
- `DELETE /api/v1/posts/{id}` - Delete a post

### Comments:
- `POST /api/v1/posts/{id}/comments` - Add a comment to a post
- `GET /api/v1/comments/{id}` - Get a specific comment
- `PUT /api/v1/comments/{id}` - Update a comment
- `DELETE /api/v1/comments/{id}` - Delete a comment

### Friendships:
- `POST /api/v1/friendships` - Send a friend request
- `GET /api/v1/friendships` - List friend requests
- `PUT /api/v1/friendships/{id}` - Accept/Reject a friend request
- `DELETE /api/v1/friendships/{id}` - Remove a friend

## Database Structure
Here is a brief overview of the database structure:

- **Users:** Stores user information.

- **Pets:** Stores pet profiles linked to users.

- **Posts:** Stores posts made by pets.

- **Comments:** Stores comments on posts.

- **Friendships:** Manages friendship relations between pets.

- **Messages:** Stores private messages between pets.

- **Notifications:** Manages notifications for users.

## Running Tests
```bash
php artisan test
 ````
 Make sure you have the test environment configured correctly in your `.env` file.


## Contributing
Contributions are welcome! However, all contributors must be authorized by the administrator before submitting changes. Please fork this repository, make your changes, and submit a pull request. Ensure your code adheres to the project's coding standards and includes appropriate tests.

## License
This project is open-sourced software licensed under the [MIT license]().