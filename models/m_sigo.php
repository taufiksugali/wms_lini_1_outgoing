<?php
class Sigo
{
    function __construct($conn)
    {
        $this->mysqli = $conn;
    }

    public function getSigoAPI()
    {
        $db = $this->mysqli->conn;
        $sql = "SELECT * 
			FROM `api`
			where `api_name` = 'api_ap_2'
			AND `status` = 1
			LIMIT 1";
        $query = $db->query($sql)->fetch_object() or die($db->error);
        return ($query);
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
            $sql = "INSERT INTO sigo";
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

    public function getStoreData($paymentId)
    {
        try {
            $db = $this->mysqli->conn;
            $sql = "SELECT *
                    FROM sigo
                    WHERE invoice_id = '$paymentId'
                    AND sigo_action = 'insert'";
            $query = $db->query($sql);
            if ($query->num_rows > 0) {
                return $query->fetch_object();
            } else {
                throw new Exception('data not found');
            }
        } catch (Exception $e) {
            return null;
        }
    }

    public function updateData($paymentId, $data)
    {
        try {
            $number = 0;
            $string = '';
            foreach ($data as $column => $value) {
                if ($number == 0) {
                    $string .= $column . "='" . $value . "'";
                } else {
                    $string .= ", " . $column . "='" . $value . "'";
                }
                $number++;
            }
            $db = $this->mysqli->conn;
            $sql = "UPDATE sigo SET ";
            $sql .= $string;
            $sql .= " WHERE sigo_id = '$paymentId'";
            $query = $db->query($sql);

            return 'updated';
        } catch (Exception $e) {
            return null;
        }
    }
}
