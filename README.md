# Task Management System API

## Overview

The **Task Management System** is a RESTful API built with Laravel that allows users to manage tasks. Users can **create**, **update**, **view**, **delete**, and **assign tasks** with various attributes like:

- **status**
- **date_due**
- **priority**
- **description**
- **title**
- **assigned_to**

### Key Features:
- Role-based access control with three roles: **Admin**, **Manager**, and **User**.
- Strong focus on model understanding, using Laravel features such as `.timestamps`, `fillable`, `guarded`, `primaryKey`, and `table`.
- Role-specific task management capabilities.
- Advanced role management for controlling task permissions.
- Soft delete functionality and task filtering using query scopes.


## Model Features
- **Timestamps**: Automatically manages `created_at` and `updated_at`.
- **Fillable and Guarded**: Ensures mass assignment protection.
- **Primary Key and Table Customization**: Customizes the primary key and table names as necessary.

## Soft Deletes
- Tasks and users are soft-deleted, meaning they are not permanently removed from the database but are flagged as deleted, allowing for possible restoration later.

## Query Scopes
- The system allows filtering tasks by `priority` and `status` using query scopes, enhancing flexibility for task management.

## Role Management Advanced

1. **Admin**:  
   - Full permissions (create, update, assign, and delete tasks for all users).
2. **Manager**:  
   - Can assign tasks and manage tasks they created or assigned.
3. **User**:  
   - Can only update the status of tasks assigned to them.

## API Endpoints

### Task Management

- **POST /tasks/**  
  Create a new task.
  
- **GET /tasks/**  
  View all tasks with optional filtering by `priority` and `status`.

- **GET /tasks/{id}**  
  View details of a specific task.

- **PUT /tasks/{id}**  
  Update a task (only the user assigned to the task can edit it).

- **DELETE /tasks/{id}**  
  Soft delete a task.

### Task Assignment

- **POST /tasks/{id}/assign**  
  Assign a task to a user (only **Managers** can assign tasks).

### User Management

- **POST /users/**  
  Create a new user.

- **GET /users/**  
  View all users.

- **PUT /users/{id}**  
  Update user information (only **Admins** can update user details).

- **DELETE /users/{id}**  
  Soft delete a user.
---

### Steps to Run the System


- [Installation](#installation)
 1. **Clone the repository:**
 
     ```bash
     git clone https://github.com/HusseinIte/TaskManagement.git
     cd TaskManagement
     ```
 
 2. **Install dependencies:**
 
     ```bash
     composer install
     npm install
     ```
 
 3. **Copy the `.env` file:**
 
     ```bash
     cp .env.example .env
     ```
 
 4. **Generate an application key:**
 
     ```bash
     php artisan key:generate
     ```
 
 5. **Configure the database:**
 
     Update your `.env` file with your database credentials.
 
 6. **Run the migrations:**
 
     ```bash
     php artisan migrate --seed
     ```
 7. **Run the seeders (Optional):**
 
     If you want to populate the database with sample data, use the seeder command:
 
     ```bash
     php artisan db:seed
     ```
 8. **Serve the application:**
 
     ```bash
     php artisan serve
     ```
