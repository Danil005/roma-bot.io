<?php

namespace Roma\Commands;



class CommandsController
{
    private static $dir = 'src/Commands';

    public static function execute(array $object)
    {
        $files = scandir(self::$dir, 0);
        for($i = 2; $i < count($files); $i++) {
            if( $files[$i] != 'CommandsController.php' ) {
                $class = 'Roma\Commands\\' . (string) explode('.', $files[$i])[0];
                (new $class($object))->execute();
            }
        }
    }
}