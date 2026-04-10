<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Exported from Figma">
    <title>Exclusive - @yield('title')</title>
    <link href="https://fonts.googleapis.com/css?family=Inter|Poppins&display=swap" rel="stylesheet">
    @yield('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    @vite('resources/css/app.css')
</head>

<body>
    @yield('content')

</body>

</html>
