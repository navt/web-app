<?php
class User {

    public function __construct(object $db) {
        $this->DB = $db;
    }

    public function exists(string $login, string $password):void {
        $sql = sprintf("SELECT * FROM `users` WHERE email = '%s'", $login);
        $this->result = $this->DB->query($sql);

        if ($this->result->num_rows == 0) {
            H::giveJson([["error" => "incorrect pair login/password #2"]]);
        }

        
    }
}