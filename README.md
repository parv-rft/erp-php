# School Management System

A comprehensive web application built with PHP (CodeIgniter 3 framework) designed to manage various aspects of a school environment. It provides distinct interfaces and functionalities for different user roles like administrators, teachers, students, parents, accountants, librarians, and HRM staff.

## Features

*   **User Management:** Separate login and dashboards for Admin, Teacher, Student, Parent, Accountant, Librarian, HRM.
*   **Academic Management:**
    *   Manage Classes, Sections, Subjects
    *   Class Routines (Timetables)
    *   Academic Syllabus
    *   Assignments
    *   Study Materials
*   **Student Management:**
    *   Add/Edit Student Information
    *   Manage Student Categories & Houses
    *   Attendance Tracking & Reporting
    *   Exam Marks Management
*   **Employee Management (HRM):**
    *   Manage Departments & Designations
    *   Employee Information
    *   Leave Management
    *   Payroll Management
    *   Recruitment (Vacancy & Application tracking)
*   **Financial Management:**
    *   Manage Student Payments & Invoices
    *   Expense Management
*   **Library Management:**
    *   Manage Books, Categories, Authors, Publishers
*   **Hostel & Transportation:**
    *   Manage Hostels, Rooms, Categories
    *   Manage Transport Routes & Vehicles
*   **Communication & Settings:**
    *   Noticeboard / Circulars
    *   System Settings
    *   Email & SMS Settings
    *   Multi-language Support
*   **Reporting:** Generate various reports related to attendance, financials, etc.

## Tech Stack

*   **Backend:** PHP 8.1 (via Docker), CodeIgniter 3
*   **Frontend:** Likely HTML, CSS, JavaScript (based on `assets/` and `js/` directories)
*   **Database:** MySQL 8.0 (via Docker)
*   **Web Server:** Apache (via Docker)
*   **Containerization:** Docker, Docker Compose

## Prerequisites

Before you begin, ensure you have the following installed on your system:

*   [Git](https://git-scm.com/)
*   [Docker](https://www.docker.com/get-started)
*   [Docker Compose](https://docs.docker.com/compose/install/) (Usually included with Docker Desktop)

## Installation and Setup

1.  **Clone the Repository:**
    ```bash
    git clone <your-repository-url>
    cd <repository-directory>
    ```
    *(Replace `<your-repository-url>` and `<repository-directory>` with the actual URL and the name of the cloned folder)*

2.  **Build and Start Containers:**
    This command builds the PHP/Apache image defined in `Dockerfile` and starts the `app` and `db` services defined in `docker-compose.yml`.
    ```bash
    docker-compose up -d --build
    ```

3.  **Import Database Schema:**
    The initial database structure and default data are located in `database_file/school.sql`. You need to import this into the running `db` container.
    *   **Wait a few moments** for the `db` container to fully initialize after starting.
    *   Copy the SQL file into the `db` container:
        ```bash
        docker cp database_file/school.sql $(docker-compose ps -q db):/tmp/school.sql
        ```
    *   Execute the SQL file inside the `db` container:
        ```bash
        docker-compose exec db mysql -u root school -e "source /tmp/school.sql"
        ```
        *(Note: No password is required for the `root` user as configured in `docker-compose.yml` for local development.)*

## Running the Application

*   **Start:** If the containers are stopped, start them with:
    ```bash
    docker-compose up -d
    ```
*   **Stop:** To stop the running containers:
    ```bash
    docker-compose down
    ```
    *(Running `docker-compose down` **will not** delete your database data, as it is stored in a persistent Docker volume (`db_data`).)*

## Accessing the Application

*   **URL:** Once the containers are running, access the application in your web browser at:
    [http://localhost:8080](http://localhost:8080)

*   **Default Admin Credentials:**
    *   **Email:** `school@admin.com`
    *   **Password:** `12345`
    *(Other user roles likely have default credentials defined in the `database_file/school.sql` file)*

## Key Directories

*   `application/`: Contains the core CodeIgniter application code.
    *   `controllers/`: Handles incoming requests and orchestrates responses.
    *   `models/`: Interacts with the database.
    *   `views/`: Contains the HTML templates.
    *   `config/`: Application configuration files.
*   `system/`: CodeIgniter framework core files.
*   `database_file/`: Contains the database schema (`.sql` file).
*   `uploads/`: Directory for file uploads.
*   `assets/`, `js/`, `dist/`: Frontend assets (CSS, JavaScript, images, etc.).
*   `Dockerfile`: Defines the PHP/Apache application container image.
*   `docker-compose.yml`: Defines and configures the application and database services for Docker. 