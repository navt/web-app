<?php
// https://gonzalo123.com/2012/07/09/building-a-simple-dependency-injection-container-with-php/
class Auth {
    
    private object $box;
    private array $user;
    public string $token;

    public function __construct(object $box) {
        $this->box = $box;
        $this->DB = $box['db'];
        $this->jwt = $box['jwt'];
    }

    public function exists(string $login, string $password):void {
        $sql = sprintf("SELECT * FROM `users` WHERE email = '%s'", $login);
        $result = $this->DB->query($sql);
        $msg = 'incorrect pair login/password';

        if ($result->num_rows == 0) {
            H::giveJson([["error" => "$msg #2"]]);
            exit();
        }

        $this->user = $result->fetch_array(MYSQLI_ASSOC);

        if (!password_verify($password, $this->user['hash'])) {
            H::giveJson([["error" => "$msg #3"]]);
            exit();
        }

        $this->seeHeader();
    }

    public function seeHeader():void {
        $headerName = $this->box['header'];
        $s = strtoupper($headerName);
        $s = str_replace('-', '_', $s);
        $indx = 'HTTP_'.$s;

        $this->token = (isset($_SERVER[$indx])) ? $_SERVER[$indx] : ''; 
    }

    public function isThereAuth():void {
        
        if ($this->token === '') {
            H::giveJson([["error" => "token missing"]]);
            exit();
        }
        
        if ($this->jwt->analize($this->token) == true) {
            H::giveJson([["error" => "you are already logged in"]]);
            exit();
        } else {
            // generate new token
            $this->generateToken();
        }
    }

    public function tokenIsValid():bool {
        $isValid = false;

        if (($this->token !== '') && ($this->jwt->analize($this->token) == true)) {
            $isValid = true;
        }

        return $isValid;
    }

    private function generateToken():void {
        $payload = ['id' => $this->user['id'], 'iat' => 0, 'exp' => 0];

        $iat = new DateTime('now'); // 'now'
        $payload['iat'] = $iat->getTimestamp();
        // set interval
        $interval = new DateInterval('P3D');
        $interval->d = $this->box['days'];
        $interval->h = $this->box['hours'];
        $interval->i = $this->box['minutes'];
        $exp = clone $iat;
        $exp->add($interval);
        $payload['exp'] = $exp->getTimestamp();

        $this->token = $this->jwt->buildToken($payload);
        H::giveJson([["newtoken" =>
            ['created' => $payload['iat'], 'token' => $this->token] 
        ]]);
    }
}