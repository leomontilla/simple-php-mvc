<?php

require "lib/MVC/MVC.php";

\MVC\MVC::registerAutoloader();

$config = array(
    "app_path" => __DIR__,
    "controllers" => array(
        "UserController" => "\\MVC\\controllers\\UserController"
        )
    );

$mvc = new \MVC\MVC($config);

$mvc->get("/", function() use($mvc) {    
    $mvc->view()->display("index.html");
});

$mvc->get("/index", function() use($mvc) {    
    print "Index <pre>";
});

$mvc->get("/hello/[a:name]", function($name) use($mvc) {
    print_r($mvc->request()->params);
});

$mvc->get("/view", function() use($mvc) {
    $mvc->render("index.html", array("user" => "Ramon Serrano"));
});

$mvc->ajax("/ajax", function() use($mvc) {
    print $mvc->request()->request_method;
});

$mvc->post("/post", function() use($mvc) {
    print $mvc->request()->request_method;
});

$mvc->put("/put", function() use($mvc) {
    print $mvc->request()->request_method;
});

$mvc->delete("/delete", function() use($mvc) {
    print $mvc->request()->request_method;
});

//Using the app

$mvc->get("/mvc", function() use($mvc) {
    print "Using the Model View Controller App\n";

    // Resolver el error del templates_path
    // Hacer que se pueda cambiar varias veces    

    $ac = $mvc->controller('UserController');
    $ac->view()->root = __DIR__;
    $ac->view()->templates_path = "./lib/MVC/views";
    echo $ac->call('index', $mvc->request(), "index.html")->body;
});

$mvc->get("/mvc2", function() use($mvc) {
    print "Using the Model View Controller App\n";

    // Resolver el error del templates_path
    // Hacer que se pueda cambiar varias veces    

    $ac = $mvc->controller('UserController');
    $ac->view()->root = __DIR__;
    $ac->view()->templates_path = "./lib/MVC/views";
    $objResponse = $ac->call('index', $mvc->request(), "index.html");
    echo $objResponse->body;
});

$mvc->get("/mvc3", function() use($mvc) {
    print "Using the Model View Controller App\n";

    // Resolver el error del templates_path
    // Hacer que se pueda cambiar varias veces

    $ac = new \MVC\controllers\UserController;
    $ac->view()->root = __DIR__;
    $ac->view()->templates_path = "./lib/MVC/views";
    echo $ac->render_html("userController/index.html", $ac->index($mvc));
});

$mvc->group("/admin", function($route) use($mvc){
    $mvc->get($route, function() {
        print "Admin Index";
    });
    $mvc->post("$route/post", function() {
        print "Admin Index POST";
    });
    $mvc->put("$route/put", function() {
        print "Admin Index PUT";
    });
});

$mvc->notFound(function() use($mvc) {
    $mvc->render("404.html", array("uri" => $mvc->request()->url), 404);
});

$mvc->run();
