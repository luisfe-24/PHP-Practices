<?php
require_once './db/config.php';
$requestMethod = $_SERVER['REQUEST_METHOD']; //con HTML form solo se puede POST y PUT

// Manejar métodos PUT y DELETE enviados a través de formularios HTML
if (isset($_POST["_method"]) && ($_POST["_method"] == "PUT" || $_POST["_method"] == "DELETE")) {
    $requestMethod = $_POST["_method"];
}


// Verifica si existe el parámetro 'view' en la URL
if (!isset($_GET["view"])) {
    echo 'view parameter is missing';
    exit; // Termina la ejecución si el parámetro es necesario
}

// Manejo de diferentes vistas según el valor de 'view'
if ($_GET["view"] == "article") {
    require_once './models/Article.php';
    require_once './models/Tag.php';
    require_once './controllers/ArticleController.php';

    $articleController = new ArticleController($requestMethod);

    $articleController->main();

    $articleList = $articleController->getArticleList();
    $tagList = $articleController->getTag()->getTagList();
    $articleResponse = $articleController->getResponse();

    require_once './views/ArticleView.php';
} else if ($_GET["view"] == "tags") {
    require_once './models/Tag.php';
    require_once './controllers/TagController.php';
    $tagController = new TagController($requestMethod);
    $tagController->main();
    $tagList = $tagController->getTagList();
    $tagResponse = $tagController->getResponse();
    require_once './views/TagView.php';
} else {
    echo '404 Not found';
}