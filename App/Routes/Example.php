<?php
use Helper\Session;
use Helper\Connection\Database;

    (new Route())->group("/asd", function(){
        (new Route())->get("/xd", function(){
            Session::set("var", "ciao bello");
        });
        (new Route())->get("/lol", function(){
            (new View("prova"))->data(["asd" => "lol"]);
        });
    });


    (new Route())->get("/no-access", function(){
        echo "lol";
    });

?>