# Symfony + React + MongoDB Project

This project contains a Symfony backend API, a React frontend using Next.js, and MongoDB as the database. Docker is used to containerize and manage the services.

## Prerequisites

- Docker: [Install Docker](https://docs.docker.com/get-docker/)
- Docker Compose: [Install Docker Compose](https://docs.docker.com/compose/install/)

## Setup Instructions

1. **Clone the repository**:

    git clone https://github.com/mariyamadd/ets-global.git
    cd ets-global


2. **Build and start the services**:

    docker-compose up --build


3. **Access the application**:
    - Symfony API: `http://localhost:9000`
    - React Frontend: `http://localhost:3000`
    - MongoDB: `mongodb://localhost:27017`

## Useful Docker Commands

- To stop the application:

    docker-compose down


- To rebuild the services after changes:

    docker-compose up --build


- To view logs:

    docker-compose logs -f


## MongoDB

MongoDB runs on port `27017`. 

mongodb://mongodb:27017/ets_database
