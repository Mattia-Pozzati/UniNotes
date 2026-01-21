<?php namespace App\View; ?>


<!DOCTYPE html>
<html lang="it" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Uninotes') ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/css/theme.css">

    <script>
        (function () {
            const storedTheme = localStorage.getItem('theme');

            if (storedTheme === 'dark' || storedTheme === 'light') {
                document.documentElement.setAttribute('data-bs-theme', storedTheme);
            } else {
                // fallback: preferenza di sistema
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                document.documentElement.setAttribute(
                    'data-bs-theme',
                    prefersDark ? 'dark' : 'light'
                );
            }
        })();
    </script>

</head>

<body class="container">

    <?php echo View::getComponent('Base/header'); ?>

    <div class="row">
        <div class="col-1"></div>
        <main class="col-10">
            <?= $content ?? '' ?>
        </main>
        <div class="col-1"></div>
    </div>

    <?php echo View::getComponent('Base/footer'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/DarkLightToggle.js"></script>
    <script src="/js/scrollPage.js"></script>
</body>

</html>