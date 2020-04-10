## Table of Contents

* [Unplug Bot](#unplug-bot)
* [Environment setup](#environment-setup)
  * [Needed tools](#needed-tools)
  * [Docker commands](#docker-commands)
  * [Composer commands](#composer-commands)
  * [Tests](#tests)

## Unplug Bot

Bot for unplug. This bot sends an sendAnswer to a Telegram channel with a predefined template.

## Environment setup

### Needed tools

1. [Install Docker](https://www.docker.com/get-started)
2. Clone this project: `git clone https://github.com/mrsbuttercup/unplug`
3. Move to the project folder: `cd unplug`
4. Install PHP dependencies and bring up the project Docker containers with Docker Compose: `make build`
5. Check everything's up using: `$ docker-composer ps`

> Note: If you want to bring down Docker service use: `make destroy`

### Docker commands

| Command        | Description                                     |
|----------------|-------------------------------------------------|
| `make start`   | Bring up docker containers                      |
| `make destroy` | Bring down docker containers                    |
| `make rebuild` | Rebuild all the container images ignoring cache |
| `make reload`  | Reload services inside docker containers        |

### Composer commands

| Command                | Description                               |
|------------------------|-------------------------------------------|
| `make deps`            | Install dependencias in composer.json     |
| `make composer-update` | Update packages declared in composer.json | comma

### Tests

| Command     | Description                           |
|-------------|---------------------------------------|
| `make test` | Run tests inside docker PHP container |