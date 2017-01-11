<?php

$app->group(['prefix' => 'v1'], function($app){

    // Resource method created in RoutesRequest.php
    $app->resource('wh', 'WarehouseController');

});
