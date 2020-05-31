<?php

define('SAVE_SUCCESS', 'Successfully Saved!');
define('UPDATE_SUCCESS', 'Successfully Updated!');
define('DELETE_SUCCESS', 'Successfully Deleted!');
define('NO_DATA', 'No data found!');
define('DATA_FOUND', 'Result with this query!');
define('ERROR_MSG', 'Error  occurred!');
define('PER_PAGE', 5);

function resource($uri, $controller)
{
    $route = app('router');
    $route->get($uri, $controller . '@index');
    $route->post($uri, $controller . '@store');
    $route->get($uri . '/{id}', $controller . '@show');
    $route->put($uri . '/{id}', $controller . '@update');
    $route->delete($uri . '/{id}', $controller . '@destroy');
}

?>
