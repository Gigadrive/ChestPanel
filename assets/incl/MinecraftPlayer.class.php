<?php

class MinecraftPlayer {
    public static function getByUUID($uuid){
        if(existsInCache("minecraftPlayer_" . $uuid)){
            return getFromCache("minecraftPlayer_" . $uuid);
        } else {
            $player = new MinecraftPlayer($uuid);
            return $player;
        }
    }

    private $uuid;
    private $username;
    private $rank;
    private $coins;
    private $resetTokens;
    private $globalPoints;
    private $playTime;

    public function __construct($uuid){
        global $link;
        $sql = mysqli_query($link,"SELECT * FROM `users` WHERE `uuid` = '" . mysqli_real_escape_string($link,$uuid) . "'");
        if(mysqli_num_rows($sql) > 0){
            $row = mysqli_fetch_array($sql);

            $this->uuid = $row["uuid"];
            $this->username = $row["username"];
            $this->rank = $row["rank"];
            $this->coins = $row["coins"];
            $this->resetTokens = $row["resetTokens"];
            $this->globalPoints = $row["global_points"];
            $this->playTime = $row["playtime"];

            setToCache("minecraftPlayer_" . $this->uuid,$this,5*60);
            setToCache("minecraftPlayer_" . $this->username,$this,5*60);
        }
    }

    public function getUUID(){
        return $this->uuid;
    }

    public function getUsername(){
        return $this->username;
    }

    public function getRank(){
        return $this->rank;
    }

    public function getCoins(){
        return $this->coins;
    }

    public function getResetTokens(){
        return $this->resetTokens;
    }

    public function getGlobalPoints(){
        return $this->globalPoints;
    }

    public function getPlayTime(){
        return $this->playTime;
    }
}

?>