# Blog Api

The goal of this repository is to make a demo of a blog api.

## Installation
To install the api manually, run:

.. code-block:: bash
    $ cd blog && composer install

### DOCKER
#### DOCKER-DEV
    Use it for 99% of your needs...

#### ISOLATED DOCKER (`experimental`)

1. Setup project environment variables :

    Setup your project by editing the `.env` file and customize all environment variables. By default the ENV file are in symfony folder `./blog/.env`

2. Initialize/Install project dependencies :
    ```sh
    make docker-start
    ```
    If you are in your first install of project you can user another command when `docker-start` was done.
    ```sh
    make docker-c-install
    ```
    Or if you just want to update your current project packages use :
    ```sh
    make docker-c-update or make docker-c-update package/name
    ```

3. Open your favorite browser :

    * [http://localhost:8001](http://localhost:8080) (Web API - Back).
    * [http://localhost:15672](http://localhost:15672) (RabbitMq).
    * [http://localhost:8081](http://localhost:8081) (mailDev).
    * [http://localhost:8080](http://localhost:8080) (phpmyadmin).

4. Stop and clear services :

    ```sh
    sudo docker-stop
    ```

5. Stop and delete all traces of changes from skeleton :

    ```sh
    sudo make docker-clean
    ```
    That delete all files to reset skeleton at his initial state.

#### Documentation
* `Backend <backend/doc/index.rst>`
