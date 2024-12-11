# BookingTicket

## Description

**BookingTicket** is an airplane ticket reservation system that allows users to create and cancel flight tickets through a web interface. The backend is developed using PHP and utilizes an SQLite database to store ticket information.

## Features

- **Create Tickets**: Users can enter flight details to obtain a unique ticket.
- **Cancel Tickets**: Users can cancel an existing ticket by using its ticket ID.
- **Input Validation**: Validation is implemented to ensure all fields are required and that the passport ID meets the required format.

## Technologies

- **Frontend**: HTML, CSS (you can use SCSS if preferred), Vue.js
- **Backend**: PHP
- **Database**: SQLite
- **Docker**: For containerizing the application

## File Structure
project-directory/ 
├── api.php # Main API file 
├── docker-compose.yml # Docker Compose configuration file 
├── Dockerfile # Dockerfile for building the container image 
├── index.html # User interface 
├── script.js # JavaScript file for frontend logic 
└── style.css # CSS style file └── tickets.db # SQLite database file to store ticket details


## Requirements

- [Docker](https://www.docker.com/) and [Docker Compose](https://docs.docker.com/compose/) must be installed on your machine.

## How to Set Up the Project

1. **Clone the repository:**
   ```bash
   git clone <your-repository-url>
   cd <repository-name>
   ```

2. **Build and run the container:**
```bash
   docker-compose up --build
```

3. **Access the aplication:**
Once the container is up and running, visit http://localhost:8000/index.html in your browser.