<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>list objects</title>
        @vite(['resources/js/list.js'])
    </head>
    <body>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <table cellspacing="10" cellpadding="10">
        <div id="message"></div>
        @foreach($jsons as $json)
            <tr>
                <td>{{ $json->id }}</td>
                <td>{{ $json->created_at->format('d.m.Y H:i') }}</td>
                <td>{{ $json->user_id }}</td>
                <td>{{ $json->data }}</td>
                <td><a href="{{ route('json.read') }}?id={{ $json->id }}" target=_blank>read</a></td>                
                <td><a class="delete" href="{{ route('json.delete') }}?id={{ $json->id }}">delete</a></td>
                <td><a href="{{ route('json.updateform') }}?id={{ $json->id }}" target=_blank>update</a></td>
            </tr>
        @endforeach
    </table>
    <br /><br />
    {{ $jsons->links() }}
    </body>
</html>