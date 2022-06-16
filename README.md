# Model Design
## User
```
- id
- name
- email
- password
```

## FriendRequest
```
- id
- user_id
- user_email
- requestor_id
- requestor_email
- status
```

## Friend
```
- id
- user_id
- user_email
- friend_id
- friend_email
```
---

# Steps to Create Laravel Project using Docker
In this case, I am using Manjaro Linux.

## 1. Docker

1. Install Docker `sudo pacman -S docker`
2. Start Docker: `sudo systemctl start docker.service` &rarr; `sudo systemctl enable docker.service`
3. Check Docker status: `sudo systemctl status docker.service` (active)
4. Setup to run docker without sudo: `sudo groupadd docker` &rarr; `sudo usermod -aG docker $USER` &rarr; `sudo chmod 666 /var/run/docker.sock`

## 2. Docker Compose
1. Install: `yay -S docker-compose`

## 3. LazyDocker
GUI for monitoring the Docker
1. Install: `yay -S lazydocker`

## 4. PHP
1. Install: `sudo pacman -S php php-fpm php-apcu php-gd php-imap php-intl php-mcrypt php-memcached php-pgsql php-sqlite php-cgi xdebug`

## 5. MariaDB/MySQL
1. Install: `sudo pacman -S mariadb`
2. Initialize: `sudo mysql_install_db --user=mysql --basedir=/usr --datadir=/var/lib/mysql`

## 6. Composer
1. Install Composer: `sudo pacman -S composer`
2. Make global env: `composer global require "laravel/installer"`
3. Add new row to `.zshrc` &rarr; `export PATH="$HOME/.config/composer/vendor/bin:$PATH"`
4. Run: `source ~/.zshrc`

## 7. Laravel
### Installation
1. Create Laravel project: `laravel new <app_name>`
2. Initialize Laravel Sail to the project: `composer require laravel/sail --dev` \
   Laravel Sail is a light-weight command-line interface for interacting with Laravel's default Docker development environment.
3. Run command: `php artisan sail:install`
4. Start sail: `./vendor/bin/sail up`

### Laravel Sail
In Laravel, we can execute `artisan` commands.
1. Migrate default laravel tables
    ```
    sail artisan migrate
    ```

2. Create models and migrations
    ```
    sail artisan make:model ModelName -m
    // -m &rarr; to make migration
    ```

3. Create controllers
    ```
    sail artisan make:controller
    ```

# Manual Test
For manual testing, we can use `Postman`, to test whether our application can run as expected.


# Laravel Automated Testing
For automated testing, we can use laravel artisan unit test feature. By using `Laravel Sail`, we can run the test within docker containers. \
Do the following command to run the test:
```
sail artisan test
```

For this case, I have created many test cases as followed:

## 1. User Friend Request
The case here, I have created dummy users to work with, so the whole database will not be disturbed. The dummy users are `dummy@gmail.com (first user)` and `user@gmail.com (second user)`. Both users will be used on most of the cases. In the first case, the second user has successfully made a friend request to the first user. If the request has been made before, the program will delete it first before continue.

## 2. Accept Friend Request
In the second case, the first user has successfully accepted the request made by the second user.

## 3. Reject Friend Request
Since the first user has accepted the request, in the third case, the program needs to reset the request status to `pending`. Therefore, it can run the case. The first user has successfully rejected the request.

## 4. Show All Friend Request
Showing all of the user's friend requests. The fourth case has successfully passed.

## 5. Show All User's Friend Lists
Showing all of the user's friend lists. The fifth case has successfully passed.

## 6. Show Common Friends
In this case, I used the real data from the database. Because the data have been specified in the best way to run the test. The sixth case has successfully passed.

## 7. Block a User
The final case is to block the user. In this case, I have decided to add a new column to the `users` table called `blocked`, which is for storing the email of someone who blocked the user. Once the user blocked, they cannot send friend request to the requestor. The row containing the relation between both of the users also deleted from the database. The final case has successfully passed.

## Screenshots

## Postman

![Postman Preview](/resources/img/Postman%20Preview.png)

## Testing Result

![Testing](/resources/img/Testing.png)
