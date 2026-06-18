# Laravel To-Do alkalmazás rekurzív alfeladatokkal

## Beüzemelési segítség lokális futtatáshoz

1. `git clone https://github.com/meszarosmarton1007/laravel_project.git` 
2. `cd laravel_project`
3.  `git checkout master` 
4. `composer install`
5. `npm install && npm run build` 
6. `cp .env.example .env`
7. `php artisan key:generate`
8. Adatbázis fájl létrehozása:
    -**Linux esetén: **`touch database/database.sqlite` 
    -**Windows esetén: ** `New-Item -ItemType File -Path database/database.sqlite`
9. `php artisan migrate:fresh`
10. `php artisan serve`


## Időzített email emlékeztető tesztelése
A `.env` fájlban a `MAIL_MAILER=log` van beállítva. A háttérfolyamat tesztelését a `php artisan app:send-task-reminders` paranccsal lehet. Csak lokális környezetben van megvalósítva.


A legenerált HTML levelek a `storage/logs/laravel.log` fájl alján lesz látható.
