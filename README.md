<a href="https://galachat.atlassian.net/wiki/external/YTEwY2Y2MGFjZGE0NDU3NzlhY2VkZDk2NGIyNzg1ZDQ" >Тестовое задание</a> сделан на <br>
Laravel 8<br>
php 8.0<br>
mysql 5.7<br><br>

Шаги по развертыванию проекта:<br>
1) composer install<br>
2) Настройка файла `.env`<br>
3) php artisan key:generate<br>
4) php artisan migrate<br>
5) php artisan db:seed (заполниться таблица Users (15) и Boards(25))<br><br><br>

Проверял работы в postman<br>
1) POST /users![photo_2024-11-08_12-35-34](https://github.com/user-attachments/assets/7e78c714-adbb-428b-b58c-9fb4d6e65d57)<br>
2) POST /users/{userId}/score ![photo_2024-11-08_12-36-07](https://github.com/user-attachments/assets/57d912e8-8765-4aa2-bf96-2df55232a08a)<br>
3) GET /leaderboard/top ![photo_2024-11-08_12-34-38](https://github.com/user-attachments/assets/52aa1fc0-bc89-470b-9263-e177ea7ff7da)<br>
4) GET /leaderboard/rank/{$userId}  ![photo_2024-11-08_12-35-11](https://github.com/user-attachments/assets/5b0b01cc-53df-4924-9ac3-94e7db907475)<br>

 <br><br>
4ый пункт, Чтобы получить ранг определённого пользователя у меня было 2 плана. <br>
1) Посчитать за период очки пользователя, и расчитать сколько пользователей имеют более высокий или равный счёт, чем этот пользователь<br>
2) Закешировать 3 массива. 1ый кеш month_toplist, 2ой week_toplist, 3 dat_toplist. При добавлении очков пользователю, пересчитывались бы массивы. Либо полностью бы делалилсь топлисты, либо просто изменялся бы рейтинг этого пользователя, изменяя рейтинги всех, кого он обошел. Время жизни кеша 1 день. Кеш заполнялся бы по крону в 00:01 например. Либо при GET /leaderboard/top с тем period, с которым обратились<br>

<br>
Подумав, решил сделать 1ый вариант, так как оно работает быстрее если каждый раз весь топлист пользователей делать. Медленнее, но меньше ресурсов используется чем 2ой вариант.
