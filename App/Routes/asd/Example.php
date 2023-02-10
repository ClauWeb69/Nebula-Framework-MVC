<?php

    (new Route())->group("/asd", function(){
        (new Route())->get("/xd", function(){});
        (new Route())->get("/lol", [new Test, "init"]);
    });


    (new Route())->get("/no-access", function(){
        echo "lol";
    });

?>