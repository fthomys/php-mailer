<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>PhpMailer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script type="module" src="https://cdn.jsdelivr.net/npm/@material/web/all.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap">

    <style>
        :root {
            --color-primary: #A68DDB;
            --color-on-primary: #ffffff;
            --color-surface: #F6F1E9;
            --color-on-surface: #3e3a35;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: var(--color-surface);
            font-family: 'Roboto', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            background: white;
            border-radius: 28px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            padding: 3rem 2.5rem;
            width: 90%;
            max-width: 640px;
            animation: fadeIn 0.6s ease-out both;
        }

        h1 {
            font-size: 2rem;
            font-weight: 500;
            color: var(--color-on-surface);
            text-align: center;
            margin-bottom: 2rem;
        }

        md-filled-button {
            --md-filled-button-container-shape: 999px;
            --md-filled-button-label-text-color: var(--color-on-primary);
            --md-filled-button-container-color: var(--color-primary);
            font-size: 1rem;
            padding: 12px 24px;
            display: block;
            margin: 0 auto 2rem auto;
            transition: transform 0.2s ease, box-shadow 0.3s ease;
        }

        md-filled-button:hover {
            transform: translateY(-2px) scale(1.03);
            box-shadow: 0 6px 12px rgba(166, 141, 219, 0.3);
        }

        .file-list {
            border-top: 1px solid #ddd;
            padding-top: 1.5rem;
            max-height: 400px;
            overflow-y: auto;
        }

        .file-item {
            display: block;
            padding: 0.6rem 1rem;
            border-radius: 12px;
            margin-bottom: 0.5rem;
            text-decoration: none;
            color: var(--color-on-surface);
            transition: background 0.2s ease;
        }

        .file-item:hover {
            background-color: #f0e7da;
        }

        .back-link {
            font-size: 0.9rem;
            display: inline-block;
            margin-bottom: 1rem;
            color: #777;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>PhpMailer</h1>

    <md-filled-button onclick="window.location.href='http://localhost:8082'">
        PhpMailer öffnen
    </md-filled-button>


    <div class="file-list">
        <?php
        $basePath = realpath(__DIR__);
        $currentPath = isset($_GET['path']) ? realpath($basePath . '/' . $_GET['path']) : $basePath;

        if (!$currentPath || strpos($currentPath, $basePath) !== 0) {
            echo "<p>⚠️ Ungültiger Pfad</p>";
            exit;
        }

        $relativePath = str_replace($basePath, '', $currentPath);
        $items = scandir($currentPath);

        if ($currentPath !== $basePath) {
            $parent = dirname($relativePath);
            echo '<a class="back-link file-item" href="?path=' . urlencode($parent) . '">⬅ Zurück</a>';
        }

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;

            $fullPath = $currentPath . DIRECTORY_SEPARATOR . $item;
            $isDir = is_dir($fullPath);

            $display = htmlspecialchars($item);
            $encoded = urlencode(trim($relativePath . '/' . $item, '/'));

            if ($isDir) {
                echo "<a class='file-item' href='?path=$encoded'>📁 $display</a>";
            } else {
                $href = htmlspecialchars($relativePath . '/' . $item);
                echo "<a class='file-item' href='$href' target='_blank' rel='noopener noreferrer'>📄 $display</a>";
            }
        }

        ?>
    </div>
</div>

</body>
</html>
