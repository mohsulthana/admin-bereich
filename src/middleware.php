<?php
// Application middleware
// Authentication...
$app->add(new \Slim\Middleware\HttpBasicAuthentication([
    "path" => ["/admin"],
    "realm" => "Administration Panel für Gemüseeggli",
    "authenticator" => function ($arguments) {
        if (isset($_SESSION['user'])) {
            $user = unserialize($_SESSION['user']);
            return $user->getIsAdmin();
        }
        return false;
    },
    "error" => function ($request, $response, $arguments) {
        return $response->withStatus(302)->withHeader('Location', '/noaccess');
    }
]));

$app->add(new \Slim\Middleware\HttpBasicAuthentication([
    "path" => ["/myprofile", "/myabos", "/pause"],
    "realm" => "Registrierter Kunde",
    "authenticator" => function ($arguments) {
            return isset($_SESSION['user']);
    },
    "error" => function ($request, $response, $arguments) {
        return $response->withStatus(302)->withHeader('Location', '/noaccess');
    }
]));
