<?php

class Reply {
    private object $box;
    private object $DB;
    private object $auth;
    private $result;
    private $rows = [];

    public function __construct(object $box) {
        $this->DB = $box['db'];
        $this->auth = $box['auth'];
    }

    private function giveData():void {
        while($row = $this->result->fetch_array(MYSQLI_ASSOC)){
            $this->rows[] = $row;
        }

        H::giveJson($this->rows);
    }

    public function checkContent(string $content):void {
        $availableContent = ['posts', 'items'];

        if (in_array($content, $availableContent) === false) {
            H::giveJson([["error" => "no such content as {$content}"]]);
            exit();
        }
    }

    public function one(string $content, string $id):void {
        $this->checkContent($content);
        $sql = sprintf("SELECT * FROM `%s` WHERE id = %s", $content, $id);
        $this->result = $this->DB->query($sql);

        if ($this->result->num_rows == 0) {
            H::giveJson([["error" => "no such record in DB relevant content: {$content}, id: {$id}"]]);
        } else {
            $row = $this->result->fetch_array(MYSQLI_ASSOC);
            $this->auth->seeHeader();

            if ($this->auth->token !== '' && $this->auth->tokenIsValid()) {
                $row['token-is-valid'] = true;
            }

            H::giveJson([$row]);
        }
    }

    public function all(string $content):void {
        $this->checkContent($content);
        $sql = sprintf("SELECT `id`,`title`,`create_date` FROM `%s` WHERE `publish` = 1", $content);
        $this->result = $this->DB->query($sql);

        if ($this->result->num_rows == 0) {
            H::giveJson([["error" => "no records in DB relevant content: {$content}"]]);
        } else {
            $this->giveData();
        }

    }


    public function someAuth(string $from, string $to):void {

        $this->auth->seeHeader();

        if (!$this->auth->tokenIsValid()) {
            H::giveJson([["error" => "you need to auth"]]);
            exit();
        }
        
        $sql = sprintf("SELECT * FROM `posts` WHERE id >= %s AND id <= %s",
            $from, $to);
        $this->result = $this->DB->query($sql);

        if ($this->result->num_rows == 0) {
            H::giveJson(
                [["error" => "no such records in DB relevant content: posts, from: {$from}, to: {$to}"]]);
        } else {
            $this->giveData();
        }
    }

    public function someFree(string $from, string $to):void {
        
        $sql = sprintf("SELECT * FROM `posts` WHERE id >= %s AND id <= %s",
            $from, $to);
        $this->result = $this->DB->query($sql);

        if ($this->result->num_rows == 0) {
            H::giveJson(
                [["error" => "no such records in DB relevant content: posts, from: {$from}, to: {$to}"]]);
        } else {
            $this->giveData();
        }
    }

    public function takePost():object {
        $p = H::post();

        if (isset($p->publish)) {
            $p->publish = 1; 
        } elseif(is_object($p)) {
            $p->publish = 0;
        }

        return $p;
    }

    public function add(string $content):void {
        $this->checkContent($content);
        $this->auth->seeHeader();

        if (!$this->auth->tokenIsValid()) {
            H::giveJson([["error" => "you need to auth"]]);
            exit();
        }

        $p = $this->takePost();

        if ($p == null) {
            H::giveJson([["error" => "no input data"]]);
        }

        // https://habr.com/ru/post/662523/
        $mysqli = $this->DB->getConnect();
        $stmt = $mysqli->prepare("INSERT INTO `posts` 
            (`title`, `description`, `keywords`, `content`, `create_date`, `publish`)
            VALUES 
            (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $p->title, $p->description, $p->keywords, $p->content, date('Y-m-d H:i:s', time()), $p->publish);
        $this->result = $stmt->execute();

        if ($this->result == false) {
            H::giveJson([["error" => "record not added"]]);
        } else {
            H::giveJson([["success" => "record added"]]);
        }
        
    }

    public function update(string $content):void {
        $this->checkContent($content);
        $this->auth->seeHeader();

        if (!$this->auth->tokenIsValid()) {
            H::giveJson([["error" => "you need to auth"]]);
            exit();
        }

        $p = $this->takePost();

        if ($p == null) {
            H::giveJson([["error" => "no input data"]]);
        }

        $mysqli = $this->DB->getConnect();
        $stmt = $mysqli->prepare(
            "UPDATE `posts` SET `title` = ?, `description` = ?, `keywords` = ?, `content` = ?, `publish` = ? WHERE `posts`.`id` = ?"
        );
        $stmt->bind_param("ssssii", $p->title, $p->description, $p->keywords, $p->content, $p->publish, $p->id);
        $this->result = $stmt->execute();

        if ($this->result == false) {
            H::giveJson([["error" => "record not updated"]]);
        } else {
            H::giveJson([["success" => "record updated"]]);
        }

    }

    public function delete():void {
        $this->auth->seeHeader();

        if (!$this->auth->tokenIsValid()) {
            H::giveJson([["error" => "you need to auth"]]);
            exit();
        }

        $p = H::post();
        
        if (!isset($p->id) || !is_numeric($p->id)) {
            H::giveJson([["error" => "id is not correct"]]);
            exit();
        }

        $sql = sprintf("DELETE FROM `posts` WHERE id = %s", $p->id);
        $this->result = $this->DB->query($sql);

        if ($this->result == false) {
            H::giveJson([["error" => "record not deleted"]]);
        } else {
            H::giveJson([["success" => "record deleted"]]);
        }
        
    }

    public function auth() {

        $p = H::post();

        if (!filter_var($p->login, FILTER_VALIDATE_EMAIL)) {
            H::giveJson([["error" => "incorrect pair login/password #1"]]);
            exit();
        }
 
        $this->auth->exists($p->login, $p->password);
        $this->auth->isThereAuth();
    }
}
