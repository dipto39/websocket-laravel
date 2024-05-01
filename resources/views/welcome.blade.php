<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Document</title>
</head>
<body>
    {{ Auth::id() }}
    @vite('resources/js/app.js')
</body>
<script>
    console.log({{ Auth::id() }});
    setTimeout(() => {
        window.Echo.channel('testChannel').listen('SendMessage', (e) => {
            console.log(e);
        })
    }, 200);
    setTimeout(() => {
        window.Echo.private('chat.{{ Auth::id() }}')
        .listen('PrivateMessage', (e) => {
            console.log(e);
        })
    }, 200);
   
</script>

</html>