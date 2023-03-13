<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>read</title>
        @vite(['resources/js/read.js'])
    </head>
    <body>
    <table cellspacing="10" cellpadding="10">
            <tr>
                <td>{{ $json->id }}</td>
                <td>{{ $json->created_at->format('d.m.Y H:i') }}</td>
                <td>{{ $json->user_id }}</td>
            </tr>
    </table>
    </body>
    <script>
        var myJson = {!! $json->data !!};
        //console.log(myJson);
    </script>
</html>
