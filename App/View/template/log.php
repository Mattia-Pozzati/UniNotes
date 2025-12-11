<?php
// Percorso file log
$logFile = __DIR__ ."/../../../storage/logs/log.log";

// Colori per livello
$colors = [
    'DEBUG' => '#6c757d',    // grigio
    'INFO' => '#0d6efd',     // blu
    'ERROR' => '#dc3545',    // rosso
];

// Legge il file
$lines = file_exists($logFile) ? file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];


?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Log Viewer</title>
    <style>
        body {
            font-family: monospace;
            background: #f8f9fa;
            padding: 20px;
        }
        .log-line {
            padding: 4px 8px;
            margin-bottom: 2px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <h1>Log Viewer</h1>
    <?php if (empty($lines)): ?>
        <p>Nessun log disponibile.</p>
    <?php else: ?>
        <?php foreach ($lines as $line): ?>
            <?php
                // Estrae il livello (seconda parola della riga)
                preg_match('/^\S+\s+(\S+)/', $line, $matches);
                $level = $matches[1] ?? 'INFO';
                $color = $colors[strtoupper($level)] ?? '#000';
            ?>
            <div class="log-line" style="background-color: <?= $color ?>33; color: <?= $color ?>;">
                <?= htmlspecialchars($line) ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
