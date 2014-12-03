<?php

/**
 * Description of Error
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * @package MVC
 */

namespace MVC;

class Error
{

    public static function run(\Exception $e)
    {
        print '<meta charset="UTF8">';
        print "<h1>MVC Framework</h1>";
        print "<p><b>Error:</b> {$e->getMessage()}</p>";
        print "<p><b>File:</b> {$e->getFile()}</p>";
        print "<p><b>Line:</b> {$e->getLine()}</p>";
        print "<p><b>Code:</b> {$e->getCode()}</p>";
        print "<p><b>Trace:</b></p>";
        print str_replace("\n", '<br>', $e->getTraceAsString());
    }

}