## Table of Contents

* [Environment setup](#environment-setup)
  * [Needed tools](#needed-tools)
  * [Environment configuration](#environment-setup)
  * [Application execution](#application-execution)
  * [Tests execution](#tests-execution)
  
## Environment setup

### Needed tools

1. [Install Docker](https://www.docker.com/get-started)
2. Clone this project: `git clone https://github.com/mrsbuttercup/unplug.git`
3. Move to the project folder: `cd unplug`
4. Install PHP dependencies and bring up the project Docker containers with Docker Compose: `make build`
5. Check everything's up using: `$ docker-composer ps`. It should show `app` service up.

> Note: If you want to bring down Docker service use: `make destroy`

### 🛠️ Environment configuration

1. You are going to need the `telegram token` to interact with your bot endpoints.
    
   To set this parameter: 
    
    ```bash
    $ touch .env.local
    $ echo 'TELEGRAM_TOKEN=XXX' >> .env.local # where XXX is the desired string value
    ```

### Application execution

Go to [http://localhost:30081/](http://localhost:30081/)

### Tests execution

Execute PHP Unit tests: `make test`