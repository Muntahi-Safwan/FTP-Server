<?php
    require_once __DIR__ . '/../config/db.php';
    require_once __DIR__ . '/../model/category_model.php';
    require_once __DIR__ . '/../model/content_model.php';

    // ── Show home page (all visitors) ──────────────────
    function showHome($pdo)
    {
        $categories  = getTopLevelCategories($pdo);
        $highlighted = getHighlightedContents($pdo, 6);
        $activeCategory = null;
        $subCategories  = [];
        $contents       = [];
        require_once __DIR__ . '/../view/home.php';
    }

    // ── Show category page ──────────────────────────────
    function showCategory($pdo, $categoryId)
    {
        $categories     = getTopLevelCategories($pdo);
        $activeCategory = getCategoryById($pdo, $categoryId);
        $subCategories  = getSubCategories($pdo, $categoryId);
        $contents       = getContentsByCategory($pdo, $categoryId);
        $highlighted    = [];
        require_once __DIR__ . '/../view/home.php';
    }
?>


