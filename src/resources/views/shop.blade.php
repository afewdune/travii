<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
    @vite(['resources/css/app.scss','resources/js/app.js'])
</head>
<body id="traviiBG">
    <div id="app">
        <shop-component :user="{{ json_encode(Auth::user()) }}"></shop-component>
    </div>
</body>
</html>