<?php
class Hubnet
{
    private $mysqli;
    function __construct($conn)
    {
        $this->mysqli = $conn;
    }

    public function insertData($data)
    {
        try {
            $number = 0;
            $columns = "(";
            $values = "(";
            foreach ($data as $column => $value) {
                if ($number == 0) {
                    $columns .= $column;
                    $values .= "'" . $value . "'";
                } else {
                    $columns .= "," . $column;
                    $values .= "," . "'" . $value . "'";
                }
                $number++;
            }
            $columns .= ")";
            $values .= ")";
            $db = $this->mysqli->conn;
            $sql = "INSERT INTO hubnet";
            $sql .= $columns;
            $sql .= " VALUES";
            $sql .= $values;
            $query = $db->query($sql);
            // var_dump($sql);
            // die();

            return 'inserted';
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    public function getHubnetAPI()
    {
        $db = $this->mysqli->conn;
        $sql = "SELECT * 
			FROM `api`
			where `api_name` = 'api_hubnet'
			AND `status` = 1
			LIMIT 1";
        $query = $db->query($sql)->fetch_object() or die($db->error);
        return ($query);
    }
}
