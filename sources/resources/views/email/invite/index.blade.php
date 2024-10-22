<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
</head>
<body style="background-color: #f8fafc;">
    <!-- HEADER -->
    <div style="
        display: block;
        height: 70px;
        padding: 6px;
    ">
        <img src="https://sun9-78.userapi.com/impg/5PWOED6aeUkUUIVrFQ4SV_f0kReS0W0N82tBLw/lrsuuOdmOlY.jpg?size=980x112&quality=95&sign=e909f7068aa27189391b02718a59ef3f&type=album" alt="SportExperts" style="
                    width: 100%;
                    display: block;
                    height: 55px;
                ">
    </div>
    <div style="color: #222; text-align: center;">
        <br>
        <h1 style="text-align: center;">Вас пригласили на событие!</h1>
        <a href="{{ $host }}:{{ $port }}/registration/?invite_user_id={{ $attributes['invited_user_id'] }}&&event_id={{ $attributes['event_id'] }}">Записаться</a>
        <br>
    </div>
    <!-- FOOTER -->
    <div style="color: #222; text-align: center;">
        <hr>
        <p>
            <b>Хорошего дня!</b>
        </p>
        <p>С уважением, команда SportExperts.</p>
        <br>
    </div>
</body>
</html>


