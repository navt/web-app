<?php

class H {
    
    public static function e(string $var):string {
        return str_replace(
            ['&', '<', '>', '"', "'", '/'], 
            ['&amp;', '&lt;', '&gt;', '&quot;', '&apos;', '&sol;'], 
            $var);
    }

    public static function giveJson(array $replyArray):void {
        $s = json_encode($replyArray);
        header("Content-type: application/json;");
        echo $s;
    }

    public static function go(string $to):void {
        $l = sprintf("Location: %s", $to);
        header($l);
        exit();
    }

    public static function post() {
        $out = ($_POST != []) ? (object)$_POST : null;
        return $out;
    }
}
