<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

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
