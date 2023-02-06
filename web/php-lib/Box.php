<?php
class Box extends Pimple {
    
    public function __construct(string $path) {
        parent::__construct();

        if (!file_exists($path)) {
            throw new InvalidArgumentException("No such file as $path");
        }

        $a = parse_ini_file($path);
        
        foreach ($a as $id => $v) {
            $this[$id] = $v;
        }

        $this->closures();
    }

    private function closures() {
        $this['router'] = function ($c) {
            return new AltoRouter();
        };

        $this['db'] = function ($c) {
            return new PlainDB(
                $c['host'], $c['dbuser'], $c['password'], $c['dbname'], $c['charset']
            );
        };
        
        $this['reply'] = function($c) {
            return new Reply($c);
        };
        
        $this['jwt'] = function($c) {
            return new Jwt($c['secret'], $c['algorithm']);
        };

        $this['user'] = function ($c) {
            return new User($c);
        };

    }
}
