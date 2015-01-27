<?php

include_once './Location.php';

class DataSource {

    private $db;

    public function __construct() {
        $this->db = new PDO('mysql:host=mysql51.websupport.sk;dbname=kamnabic;port=3309', 'tlhl3ze3', 'jq78Nh234Pm');
    }

    public function getAll() {
        $st = $this->db->prepare("SELECT l.*, c.name as county_name FROM location l 
                                    JOIN county_location cl ON (l.id=cl.location_id)
                                    JOIN county c ON(c.id=cl.county_id)");
        $st->execute();
        return $st->fetchAll(PDO::FETCH_OBJ);
    }

    public function getByDifficulty($difficulty) {
        $st = $this->db->prepare("SELECT * FROM location WHERE difficulty=?");
        $st->execute(array($difficulty));
        return $st->fetchAll(PDO::FETCH_OBJ);
    }

    public function getByLength($type = 'less', $length) {
        $mark = '<';
        if ($type === 'more') {
            $mark = '>';
        }
        $sql = "SELECT * FROM location WHERE length" . $mark . "?";
        $st = $this->db->prepare($sql);
        $st->execute(array($length));
        return $st->fetchAll(PDO::FETCH_OBJ);
    }

    public function get($alias) {
        $st = $this->db->prepare("SELECT l.*, c.name as county_name FROM location l 
                                    JOIN county_location cl ON (l.id=cl.location_id)
                                    JOIN county c ON(c.id=cl.county_id)
                                    where l.alias = ?");
        $st->execute(array($alias));
        $location = $st->fetch(PDO::FETCH_OBJ);
        return $location;
    }

    public function getSuggested() {
        $st = $this->db->prepare("SELECT * FROM location WHERE is_suggested = ?");
        $st->execute(array('yes'));
        return $st->fetchAll(PDO::FETCH_OBJ);
    }

    public function getByCounty($county) {
        $st = $this->db->prepare("SELECT l.*, c.name as county_name FROM location l JOIN county_location cl ON (l.id=cl.location_id)
                                    JOIN county c ON(c.id=cl.county_id)
                                    where c.name = ?");
        $st->execute(array($county));
        return $st->fetchAll(PDO::FETCH_OBJ);
    }

    public function updateLocation($attribute, $value, $location) {
        $st = $this->db->prepare("UPDATE location SET $attribute = ? WHERE id = ?");
        $test = $st->execute(array($value, $location->id));
        var_dump($test);
        var_dump($attribute);
        var_dump($value);
        var_dump($location->id);
    }

}
