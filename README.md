<img src="https://avatars.githubusercontent.com/u/153977186?s=400&u=7268ad55ed694cec25c1467486710abb82bc9ad8&v=4" style="width: 10%">
<h2>Remote-Siberian-Hammer</h2>
<br>
<h3>Используемые технологии:</h3>
<ul>
    <li>
        <a href="#">PHP 8.3 (Laravel 10)</a>
    </li>
    <li>
        <a href="#">PostgreSQL 13</a>
    </li>
    <li>
        <a href="#">MongoDB 4</a>
    </li>
    <li>
        <a href="#">Python 3</a>
    </li>
    <li>
        <a href="#">JavaScript (Vue 3)</a>
    </li>
    <li>
        <a href="#">RabbitMQ</a>
    </li>
</ul>
<hr>
<h3>Настройка окружения.</h3>
<ul>
    <li>
        <p>
            <span>Поднять docker:</span><br>
            <b style="color: #222;">(sudo) docker-compose up --build</b>
        </p>
    </li>
    <br />
    <li>
        <p>
            <span>Создать/настроить конфиг, как минимум надо настроить подключение к БД:</span><br>
            <b style="color: #222;">(sudo) cp .example.env .env</b>
        </p>
    </li>
    <br />
    <li>
        <p>
            <span>Установить зависимости:</span><br>
            <b style="color: #222;">(sudo) docker-compose run php-fpm composer install</b><br>
            <b style="color: #222;">(sudo) docker-compose run node npm install</b><br>
        </p>
    </li>
    <br />
    <li>
        <span>Создать миграции: <small>(Нужно проверить есть ли база)</small></span><br>
        <b style="color: #222;">(sudo) docker-compose run php-fpm artisan migrate</b><br>
    </li>
    <br />
    <li>
        <span>Запустить сборку фронтенда:</span><br>
        <b style="color: #222;">(sudo) docker-compose run node run build</b><br>
    </li>
</ul>
<hr />
<h3>Дополнительно:</h3>
<div style="padding: 0.7em;background-color: #edf2f7;border-radius: 0.4em;margin-bottom:10px;">
    <p>
        API описано по спецификации <b>JSON-RPC</b>. Саму спецификацию можно посмотреть тут:<br />
        <a href="https://www.jsonrpc.org/specification">https://www.jsonrpc.org/specification</a><br />
        <small>В проекте используется версия <span style="color: red;">2.0</span></small>
    </p>
</div>
<div style="padding: 0.7em;background-color: #edf2f7;border-radius: 0.4em;margin-bottom:10px;">
    <p>
        Ссылка на документацию по методам API:<br />
        <a href="https://www.postman.com/gold-satellite-925746/workspace/sportexperts/overview">
            https://www.postman.com/gold-satellite-925746/workspace/sportexperts/overview
        </a><br />
    </p>
</div>
<div style="padding: 0.7em;background-color: #edf2f7;border-radius: 0.4em;margin-bottom:10px;">
    <p>
        Файл schema.drawio можно открыть в приложении <b>draw.io</b> или на сайте:<br />
        <a href="https://app.diagrams.net/">https://app.diagrams.net/</a><br />
    </p>
</div>