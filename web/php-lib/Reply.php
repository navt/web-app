<?php

class Reply {
    private object $DB;
    private $result;

    public function __construct(object $db) {
        $this->DB = $db;
    }

    private function giveData():void {
        while($row = $this->result->fetch_array(MYSQLI_ASSOC)){
            $rows[] = $row;
        }

        H::giveJson($rows);
    }

    public function checkContent(string $content):void {
        $availableContent = ['posts'];

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
            $this->giveData();
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

    public function some(string $content, string $from, $to):void {
        $this->checkContent($content);
        $sql = sprintf("SELECT * FROM `%s` WHERE id >= %s AND id <= %s",
            $content, $from, $to);
        $this->result = $this->DB->query($sql);

        if ($this->result->num_rows == 0) {
            H::giveJson(
                [["error" => "no such records in DB relevant content: {$content}, from: {$from}, to: {$to}"]]);
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

    // https://ru.stackoverflow.com/questions/193978/
    public function add(string $content):void {
        $this->checkContent($content);
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
            H::go('/');
        }
        
    }

    public function update(string $content):void {
        $this->checkContent($content);

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
            H::go('/');
        }

    }

    public function delete():void {
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
            H::go('/edit/0');
        }
        
    }

    public function auth() {
        $p = H::post();
        $msg = [["error" => "incorrect pair login/password #1"]];

        if (!filter_var($p->login, FILTER_VALIDATE_EMAIL)) {
            H::giveJson($msg);
            exit();
        }
        // есть ли такой юзер, совпадают ли хеши паролей?
        // - да -> смотрим его токен, если токен действительный, пернаправляем
        // на редактирование. Если токен старый, выдаем новый токен
        // - нет -> отправляем обратно на /auth 
        

    }

}
