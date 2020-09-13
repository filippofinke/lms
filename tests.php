<?php

use FilippoFinke\LMS\Api;

require __DIR__ . '/vendor/autoload.php';

$api = new Api();

$result = $api->login(readLine("[?] AVS: "), readLine("[?] Password: "));
var_dump($result);
if($result) {
    $class = new ReflectionClass('FilippoFinke\LMS\Api');
    foreach($class->getMethods() as $method) {
        if($method->isPublic() && $method->getNumberOfParameters() == 0) {
                echo str_pad(" START ".$method->getName()."() ", 50, "#", STR_PAD_BOTH)."\n";
                $result = $api->{$method->getName()}();
                var_dump($result);
                echo str_pad(" END ".$method->getName()." ", 50, "#", STR_PAD_BOTH)."\n\n";
        }
    }
}
?>