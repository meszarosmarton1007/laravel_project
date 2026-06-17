# Laravel To-Do alkalmazás rekurzív alfeladatokkal

## Beüzemelési segítség lokális futtatáshoz

1. `git clone https://github.com/meszarosmarton1007/laravel_project.git` ---> `cd laravel_project` --> master branch-ben van a projekt--> `git checkout mastert` 
2. `composer install`
3. `npm install && npm run build` windows esetén `New-Item -ItemType File -Path database/database.sqlite`
4. `cp .env.example .env`
5. `php artisan key:generate`
6. `touch database/database.sqlite`
7. `php artisan migrate:fresh`
8. `php artisan serve`


## Időzített email emlékeztető tesztelése
A `.env` fájlban a `MAIL_MAILER=log` van beállítva. A háttérfolyamat tesztelését a `php artisan app:send-task-reminders` paranccsal lehet.
A legenerált HTML levelek a `storage/logs/laravel.log` fájl alján lesz látható.
