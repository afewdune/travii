<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leader Board</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

@vite(['resources/css/app.scss','resources/js/app.js'])
<body id="leaderboardBG">

    <div id="app">
        <leaderboard-component 
            :user="{{ json_encode(Auth::user()) }}"
            :leaderboard="{{ json_encode($leaderboard) }}">
        </leaderboard-component>
    </div>
    <effect></effect>
</body>
</html>