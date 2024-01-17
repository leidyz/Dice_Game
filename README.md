# Dice_Game
Below are the API routes for the DiceGame application. These routes define the endpoints for user registration, authentication, game playing, and player management. The routes are categorized based on their access permissions.

**Public Routes (Authentication)**

**Register a New Player**
- Endpoint: POST /api/players
- Controller Method: register
- Description: Allows users to register as players in the system.

**Player Login**
- Endpoint: POST /api/login
- Controller Method: login
- Description: Allows registered players to log in.

**Player Logout**
- Endpoint: POST /api/logout
- Controller Method: logout
- Middleware: auth:api
- Description: Logs out the authenticated player.

**Play Dice**
- Endpoint: POST /api/players/{id}/games/
- Controller Method: play
- Middleware: auth:api, role:player
- Description: Allows players to roll the dice and record the game result.

**Delete Player Rolls**
- Endpoint: DELETE /api/players/{id}/games/
- Controller Method: destroy
- Middleware: auth:api, role:player
- Description: Deletes all rolls for a specific player.

**View Player Game History**
- Endpoint: GET /api/players/{id}/games
- Controller Method: index
- Middleware: auth:api, role:player
- Description: Retrieves the game history for a specific player.

**Update Player Name**
- Endpoint: PUT /api/players/{id}
- Controller Method: update
- Middleware: auth:api, role:player
- Description: Modifies the name of a player.

**Administrator Actions**
*View All Players*
- Endpoint: GET /api/players
- Controller Method: index
- Middleware: auth:api, role:admin
- Description: Retrieves the list of all players in the system.

**View Player Ranking**
- Endpoint: GET /api/players/ranking
- Controller Method: getRanking
- Middleware: auth:api, role:admin
- Description: Retrieves the average success rate of all players.

**View Player with Worst Success Rate**
- Endpoint: GET /api/players/ranking/loser
- Controller Method: getLoser
- Middleware: auth:api, role:admin
- Description: Retrieves the player with the worst success rate.

**View Player with Best Success Rate**
- Endpoint: GET /api/players/ranking/winner
- Controller Method: getWinner
- Middleware: auth:api, role:admin
- Description: Retrieves the player with the best success rate.

**Set Up in your computer**
- Clone the Repository:
git clone <repository-url>
- Install Dependencies:
composer install
- Generate Application Key:
php artisan key:generate
- Configure Database: Open the .env file and update the database connection details.
- Run Migrations:
php artisan migrate
- Seed the Database:
php artisan db:seed
- Install Passport:
php artisan passport:install
- Seed Roles:
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=PlayersTableSeeder
- Run Tests:
php artisan test
- Serve the Application:
php artisan serve

Using the API
After completing the setup, you can access the API using the defined routes. For example, you can use tools like Postman or curl to interact with the API.

API Base URL: http://127.0.0.1:8000/api
