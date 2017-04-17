<?php

    require('creds.php');

    function fetchPayLoad()
    {
        if ($payload = file_get_contents('php://input'))
        {
            try
            {
                return json_decode($payload);
                echo 'success';
            }
            catch (exception $e)
            {
                echo $e;
                return;
            }
        }
        else
            return "";
    }

    $userLogin = fetchPayLoad();
    var_dump($userLogin);

    if($userLogin = "")
        die();




 /*   $userName = $userLogin["username"];
    $password = $userLogin["password"];

    var_dump($userName);
    var_dump($password);*/