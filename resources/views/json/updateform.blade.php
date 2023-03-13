<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>update form</title>
        @vite(['resources/js/update.js'])
    </head>
    <body>
    <div id="message"></div>
    <form method="POST" action="{{ route('json.update') }}">
        @csrf
        <input id="id" type="hidden" value="{{ $json->id }}">
        <input id="usertoken" type="text" placeholder="token" value="" required><br/ >
        <textarea id="data" rows="10" cols="45" placeholder="$data->list->sublist[0]=0" value="" required></textarea><br />
        Request Type (check for post): <input id="reqType" type="checkbox"> <br /><br />
        <input id="button" type="button" value="Send Data">
    </form>
    </body>
</html>