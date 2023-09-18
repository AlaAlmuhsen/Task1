<?php

declare(strict_types=1);

spl_autoload_register(function ($class) {
  require __DIR__. "/src/$class.php";
});
spl_autoload_register(function ($class) {
  require __DIR__. "/src/Controllers/$class.php";
});


require('./src/Gateway/UsersGateway.php');
require('./src/Gateway/QuestionsGateway.php');
require('./src/Gateway/CommentsGateway.php');
require('./src/Controllers/QuestionController.php');
require('./src/Controllers/CommentController.php');

set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleExeption");

header('Content-Type: application/json; charset=utf-8');

$parts = explode("/",$_SERVER["REQUEST_URI"]);

if($parts[1] != "api"){
  http_response_code(404);
  exit();
}

// var_dump($parts);


$database = new Database("localhost", "Task1","alaa","");

switch($parts[2]){
  case "user":
    $gateway = new UsersGateway($database);
    $controller = new UserController($gateway);
    $controller->processRequest($_SERVER["REQUEST_METHOD"],$parts[3]);
    break;
  case "question":
    $id = $parts[3] ?? null;
    $gateway = new QuestionsGateway($database);
    $controller = new QuestionController($gateway);
    $controller->processRequest($_SERVER["REQUEST_METHOD"],$id);
    break;
  case "comment":
    $id = $parts[3] ?? null;
    $gateway = new CommentsGateway($database);
    $controller = new CommentController($gateway);
    $controller->processRequest($_SERVER["REQUEST_METHOD"],$id);
    break;
}



?>