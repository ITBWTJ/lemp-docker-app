<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons" rel="stylesheet">
</head>
<body>

    <div id="app">

    </div>
    <script src="/js/build.js"></script>
    <?php if (false): ?>
        <?php foreach ($posts as $post): ?>
            <h1>Post # <?= $post['id']; ?></h1>
            <div><?= $post['message']; ?></div>
            <div>Author: <?= $post['name'] .' '. $post['email']; ?></div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
