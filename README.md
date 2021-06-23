## Symfony 5 CRUD


### Running the code
To run the app follow these steps:

1. Running `git clone https://github.com/salman-shafique/symfony5-crud.git`
2. Create a `.env` file within the root folder of the project or copy example env `cp .env.example .env`
3. Run `docker-composer up --build` to spin up the MySQL container.
4. Run `composer install` to install all the project's dependencies.
5. Run `yarn install` to install all the frontend dependencies.
6. Run `yarn run dev` to build the assets.
7. Run `php bin/console doctrine:migrations:migrate` to run the migrations.
8. Run `symfony console doctrine:fixtures:load` to run the admin seeder.
9. Run `symfony server:start --no-tls` to run the server.


