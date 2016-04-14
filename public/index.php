<?php
include __DIR__."/../vendor/autoload.php";

\MyApp\Db::$pdo = new \PDO("sqlite:".__DIR__."/../db.sqlite");

$container = [
    'template' => new Twig_Environment(
        new Twig_Loader_Filesystem(__DIR__."/../template"),
        ['debug' => true]
    )
];

$routing_map = [
    ['GET',      '/',            '\MyApp\Post::showList' ],
    ['GET',      '/post/:id',    '\MyApp\Post::show',  ['id' => '/^(\d+)$/']],
    ['POST',     '/post/create', '\MyApp\Post::create' ],
    ['GET',      '/reset',       '\MyApp\Post::reset' ],
    '#404'       =>              '\MyApp\Post::notfound'
];

$router = new \Teto\Routing\Router($routing_map);
$action = $router->match($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

if (!preg_match('|\A([\\a-zA-Z0-9_]*)::([a-zA-Z0-9_]+)\z|u', $action->value, $matches))
    throw new \InvalidArgumentException('invalid action string:' . $action->value);

$method_name = $matches[2];
(new $matches[1]($container))->$method_name($action->param);
