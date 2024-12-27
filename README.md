# Educational Platform

A platform where teachers can create and manage lessons for their groups in an intuitive and enjoyable way. The main focus is on easy deployment and initial scalability for smaller target audiences.

⚠️ **Project still "Work In Progress"** ⚠️

## Technology Stack

### Backend

-   **Laravel** - chosen for its simplicity, convenience, and ease of application development
-   **PostgreSQL** - as the database for its reliability and performance
-   **AWS S3** - for storing uploaded images, ensuring availability without infrastructure concerns
-   **Pusher** - for real-time notifications to ensure application scalability

### Frontend

-   **React** with TypeScript
-   **shadcn/ui** components
-   **TailwindCSS** for styling
-   **Inertia.js** for server-side rendering capabilities

## Architecture

The application follows Domain-Driven Design (DDD) principles with a ports and adapters architecture to ensure extensibility and universality. While the implementation might not strictly follow all theoretical guidelines, it maintains the core benefits of the architecture.

Key architectural decisions:

-   Stateless Laravel application for horizontal scalability
-   PostgreSQL as the primary database
-   S3 for image storage
-   Pusher for scalable notifications

## Features

### Authentication & Authorization

-   JWT-based authentication
-   OAuth2 support for GitHub and Google login

### Group Management

-   Administrators can:
    -   Create groups
    -   Add users to groups
    -   Create sections within groups
    -   Create lessons within sections

### Lesson Creation

-   Full Markdown editor support
-   Text formatting capabilities
-   Image insertion
-   Syntax highlighting for code snippets
-   Scheduled publishing
-   Group-specific access control

### User Features

-   Access to authorized lessons
-   Real-time notifications for new lessons

## Development Setup

### Prerequisites

-   PHP 8.3 or higher
-   Node.js
-   pnpm

### Local Development Setup

1. Clone the repository

```bash
git clone https://github.com/SekulDev/learning_platform.git
cd learning_platform
```

2. Install PHP dependencies

```bash
composer install
```

3. Install frontend dependencies

```bash
pnpm install
```

4. Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

5. Configure your `.env` file with:

-   Database credentials
-   S3 credentials
-   Pusher credentials
-   OAuth credentials

6. Run migrations

```bash
php artisan migrate
```

7. Start the development servers

```bash
php artisan serve
pnpm dev
```

## Deployment

To deploy using Docker:

```bash
docker-compose up -d --build
```

## Project Status

This project was developed as part of a challenge within several dozen hours by a single developer. Due to the time constraints and scope, some decisions were made to optimize development speed:

-   Direct commits to main branch (as a solo developer)
-   Focus on core functionality over comprehensive testing

### Areas for Improvement

-   Missing integration and E2E tests (only unit tests present)
-   Insufficient abstraction level in some cases
-   Lack of comprehensive documentation

### Learning Outcomes

The project served as an excellent learning experience with DDD and ports and adapters architecture, providing valuable insights into architectural patterns and their practical implementation.

## Contributing

I'm open to suggestions and improvements! Feel free to:

-   Submit issues
-   Propose improvements
-   Share feedback on the architecture

The goal is to learn and improve, so all constructive input is welcome.
