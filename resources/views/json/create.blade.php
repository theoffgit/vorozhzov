<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>create form</title>
        @vite(['resources/js/create.js'])
    </head>
    <body>
    <div id="message"></div>
    <form method="POST" action="{{ route('json.store') }}">
        @csrf
        <input id="usertoken" type="text" placeholder="for token" value="" required><br/ >
        <textarea id="data" rows="10" cols="45" placeholder="for data" value="" required></textarea><br />
        Request Type (check for post): <input id="reqType" type="checkbox"> <br /><br />
        <input id="button" type="button" value="Send Data">
    </form>
    </body>
</html>
