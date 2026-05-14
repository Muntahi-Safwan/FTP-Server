<?php
    require_once __DIR__ . '/navbar.php';

    $categories = $categories ?? [];
    $subCategories = $subCategories ?? [];
    $contents = $contents ?? [];
    $highlighted = $highlighted ?? [];
    $activeCategory = $activeCategory ?? null;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home — FTP Server</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: #0f0f23;
            color: #fff;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 24px 16px;
        }

        h2 {
            color: #e94560;
            margin-bottom: 16px;
        }

        /* Category Tabs */
        .tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        .tab-btn {
            padding: 8px 20px;
            background: #1a1a2e;
            color: #fff;
            border: 1px solid #e94560;
            border-radius: 20px;
            text-decoration: none;
            font-size: 14px;
        }

        .tab-btn:hover,
        .tab-btn.active {
            background: #e94560;
        }

        /* Sub-categories */
        .subcats {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 20px;
        }

        .sub-btn {
            padding: 5px 14px;
            background: #0f0f23;
            color: #aaa;
            border: 1px solid #333;
            border-radius: 14px;
            text-decoration: none;
            font-size: 13px;
        }

        .sub-btn:hover {
            color: #fff;
            border-color: #e94560;
        }

        /* Content Grid */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        .card {
            background: #1a1a2e;
            border-radius: 10px;
            padding: 18px;
            border: 1px solid #222;
        }

        .card h4 {
            color: #e94560;
            margin-bottom: 8px;
        }

        .card p {
            font-size: 13px;
            color: #aaa;
            margin: 4px 0;
        }

        .dl-btn {
            display: inline-block;
            margin-top: 12px;
            padding: 6px 16px;
            background: #e94560;
            color: #fff;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
        }

        .dl-btn:hover {
            background: #c73652;
        }

        .no-content {
            color: #aaa;
            margin-top: 20px;
        }

        /* Highlighted label */
        .section-label {
            font-size: 13px;
            color: #888;
            margin-bottom: 12px;
        }
    </style>
</head>

<body>
    <div class="container">

        <h2>📂 Browse Contents</h2>

        <!-- Category Tabs -->
        <div class="tabs">
            <a href="index.php?page=home"
                class="tab-btn <?= empty($activeCategory) ? 'active' : '' ?>">
                All
            </a>
            <?php foreach ($categories as $cat): ?>
                <a href="index.php?page=category&id=<?= $cat['id'] ?>"
                    class="tab-btn <?= (!empty($activeCategory) && $activeCategory['id'] == $cat['id']) ? 'active' : '' ?>">
                    <?= htmlspecialchars($cat['name']) ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Sub-category filters -->
        <?php if (!empty($subCategories)): ?>
            <div class="subcats">
                <span style="font-size:13px; color:#888; align-self:center;">Filter:</span>
                <?php foreach ($subCategories as $sub): ?>
                    <a href="index.php?page=category&id=<?= $sub['id'] ?>" class="sub-btn">
                        <?= htmlspecialchars($sub['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Section label -->
        <?php if (empty($activeCategory)): ?>
            <p class="section-label">⭐ Most downloaded & recently added</p>
        <?php else: ?>
            <p class="section-label">
                Showing: <strong><?= htmlspecialchars($activeCategory['name']) ?></strong>
            </p>
        <?php endif; ?>

        <!-- Content Grid -->
        <?php
        $items = !empty($contents) ? $contents : ($highlighted ?? []);
        ?>
        <?php if (empty($items)): ?>
            <p class="no-content">No contents available yet.</p>
        <?php else: ?>
            <div class="grid">
                <?php foreach ($items as $item): ?>
                    <div class="card">
                        <h4><?= htmlspecialchars($item['title']) ?></h4>
                        <p>📂 <?= htmlspecialchars($item['category_name'] ?? 'Uncategorized') ?></p>
                        <p><?= htmlspecialchars(mb_substr($item['description'], 0, 100)) ?>
                            <?= strlen($item['description']) > 100 ? '...' : '' ?>
                        </p>
                        <p style="font-size:12px; color:#555; margin-top:6px;">
                            ⬇ <?= intval($item['download_count']) ?> downloads
                        </p>
                        <a href="<?= htmlspecialchars($item['file_path']) ?>"
                            class="dl-btn" download>⬇ Download</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</body>

</html>