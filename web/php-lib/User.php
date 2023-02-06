<?php
// https://gonzalo123.com/2012/07/09/building-a-simple-dependency-injection-container-with-php/
class User {
    
    private object $box;

    public function __construct(object $box) {
        $this->box = $box;
        $this->DB = $box['db'];
        $this->jwt = $box['jwt'];
    }

    public function exists(string $login, string $password) {
        $sql = sprintf("SELECT * FROM `users` WHERE email = '%s'", $login);
        $result = $this->DB->query($sql);
        $msg = 'incorrect pair login/password';

        if ($result->num_rows == 0) {
            H::giveJson([["error" => "$msg #2"]]);
            exit();
        }

        $row = $result->fetch_array(MYSQLI_ASSOC);

        if (!password_verify($password, $row['hash'])) {
            H::giveJson([["error" => "$msg #3"]]);
            exit();
        }

        $this->seeHeader();

    }

    private function seeHeader() {
        $headerName = $this->box['header'];
        $s = strtoupper($headerName);
        $s = str_replace('-', '_', $s);
        $indx = 'HTTP_'.$s;

        $token = (isset($_SERVER[$indx])) ? $_SERVER[$indx] : null; 
        
        if ($token === null) {
            H::giveJson([["error" => "token missing"]]);
            exit();
        }

        if ($this->jwt->analize($token) == true) {
            echo 'success';
        } else {
            echo 'fail';
        }
    }
}