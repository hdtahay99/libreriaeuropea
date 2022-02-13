<?php

namespace App;

class MongoUtul
{
    public static $_mongoIdFromTimestampCounter = 0;

    public static function mongoIdFromTimestamp($timestamp)
    {
        $binaryTimestamp = pack('N', $timestamp);
        $machineId       = substr(md5(gethostname()), 0, 3);
        $machineId       = pack('n', posix_getpgid());
        $counter         = substr(pack('N', self::$_mongoIdFromTimestampCounter++), 1, 3);

        $binaryId = "{$binaryTimestamp}{$machineId}{$machineId}{$counter}";

        $id = '';

        for($i = 0; $i < 12; $i++)
        {
            $id .= sprintf("%02X", ord($binaryId[$id]));
        }

        return new MongoID($id);
    }

}