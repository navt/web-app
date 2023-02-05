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
        $this['router'] = $this->share(function ($c) {
            return new AltoRouter();
        }); 
        $this['db'] = $this->share(function ($c) {
            return new PlainDB(
                $c['host'], $c['user'], $c['password'], $c['dbname'], $c['charset']
            );
        });
        $this['reply'] = function($c) {
            return new Reply($c['db']);
        };
        $this['jwt'] = function($c) {
            return new Jwt($c['secret'], $c['algorithm']);
        }; 
    }
}
