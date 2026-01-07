<!DOCTYPE html>
<html lang="it" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Uninotes') ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/css/theme.css">
    
</head>

<body class="container-fluid">

    <?php \App\View\View::render("header", "component") ?>
    
    <div class="row">
        <div class="col-1"></div>
        <main class="col-10" >
            <?= $content ?? '' ?>
        </main>
        <div class="col-1"></div>
    </div>

    <?php \App\View\View::render('footer', "component"); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/DarkLightToggle.js"></script>
</body>

</html>