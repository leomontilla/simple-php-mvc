<?php

return array(
    array(
        "method"  => ["GET", "POST"],
        "pattern" => "/foo",
        "action"  => "MVC\\Tests\\EjemploModule\\Controller\\FooController::indexAction",
        "name"    => "foo_index"
    ),
    array(
        "method"  => ["GET", "POST"],
        "pattern" => "/foo/redirect",
        "action"  => "MVC\\Tests\\EjemploModule\\Controller\\FooController::redirectAction",
        "name"    => "foo_redirect"
    ),
    array(
        "method"  => ["GET", "POST"],
        "pattern" => "/foo/redirected",
        "action"  => "MVC\\Tests\\EjemploModule\\Controller\\FooController::redirectedAction",
        "name"    => "foo_redirected"
    )
);