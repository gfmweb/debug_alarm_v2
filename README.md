# DEBUG_ALARM_v2_BOT

## PHP v 7.4  
## WebSockets by WorkerMan
## Vue.js  + axios
## Bootstrap 5
## Redis
## MySQL


Объединённый сервис сообщений по проектам 

Просмотр логов

Оповещение участников (Два вида сообщений Критичные/Бесшумные)

Контроль законченной цепочки логов (на случай если логируемый сервис упал)

Работает на TelegramAPI v 6.0
<br>

## Настройки

### .BotFather
Установить адрес webHooks для бота (прим. https://debug.gfmweb.ru/t_hooks)

Установить кнопку открытия приложения (/setmenubutton) Для бота и задать адрес WebAPP (прим. https://debug.gfmweb.ru/telegram)

### .env

str 22 app.baseURL = 'http://debug.gfmweb.ru'


str 41-46 DB

### Config/App.php

str 27  public $baseURL = 'http://debug.gfmweb.ru/';

str 40  public $indexPage = '';

str 73  public $defaultLocale = 'ru';

str 100  public $supportedLocales = ['ru'];

str 112 public $appTimezone = 'Europe/Moscow';


### Config/Constants.php

str 17  defined('TELEGRAM') || define('TELEGRAM', '5368469368:AAFJl8klvgmm66JDfe6VdWS_IZR1ZLEXsvM');

### Развертывание

1. Точка входа в приложеии находится в папке public (Нужно отредактировать настройки WEB сервера чтобы он сразу смотрел 
в неё)

2. Выполнить php spark migrate

3. Выполнить php spark db:seed Admin  


### Первый вход администратора

При первом входе адмнистратора {'login':admin,'password':admin}
Будет создана ссылка для перехода в робота и создания первого поьзователя
Пройдя по ссылке, в открывшемся телеграм боте нужно нажать на кнопку СТАРТ.
После нажатия на кнопку создасться пользователь с логином admin и паролем admin. Ник пользователя будет Администратор.
Авторизационные сообщения на вход в админку и на вход к просмотру логов будут приходить в телеграм.

После создания пользователя. Произойдет автовыход из админки и откроется форма входа (уже нормальная, та которая 
будет ждать ввода проверочного кода после валидации пары логин/пароль).

## Админка и её фунции (ТОЛЬКО WEB Интерфейс)
1. CRUD пользователей и генерация ссылки для регистрации его через бот (для того чтобы не спрашивать его user_id 
   телеграма)
2. CRUD операции с проектами
3. Смена пароля Администратора
4. Остановка / Запуск сервиса 
5. Смена WebHook адреса
6. Реинициализация ключей Redis 

## Интерфейс пользователя (WEB)
1. Просмотр логов в режиме реального времени
2. Фильтрации списка
3. Чтение лога
4. Оповещение других пользователей о конкретном логе
5. Смена пароля

## Интерфейс пользователя (TELEGRAM_BOT)
1. Просмотр логов в режиме реального времени
2. Фильтрации списка
3. Чтение лога
4. Оповещение других пользователей о конкретном логе
