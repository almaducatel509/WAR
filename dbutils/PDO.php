<?php
class DBA {

    public  $error  = '';

    private function dbConnect()
    {
        $this->error = "";
        try {
            $db_name     = 'chcl';
            $db_user     = 'root';
            $db_password = '';
            $db_host     = 'localhost';

            $pdo = new PDO("mysql:host=" . $db_host  . ";dbname=" . $db_name, $db_user, $db_password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;

        } catch(PDOException $e) {
            $this->error = $e->getMessage();
        }
    }

    public function query($sql, $data = null)
    {
        $this->error = "";
        try {
            $pdo  = $this->dbConnect();
            if ($this->error != '') {
                return $this->error;
            }

            $stmt = $pdo->prepare($sql);


            $stmt->execute(is_array($data) ? $data : null);
            $response = [];
            // while (($row = $stmt->fetch(PDO::FETCH_ASSOC)) !== false) {
            while (($row = $stmt->fetch(PDO::FETCH_OBJ)) !== false) {
                $response[] = $row;
            }

            $pdo = null;

            $stmt->closeCursor();

            return $response;

        } catch(PDOException $e) {
            $this->error = $e->getMessage();
        }
    }

    public function executeTransaction($sql, $data)
    {
        try {
            $pdo = $this->dbConnect();

            if ($this->error != '') {
                return $this->error;
            }

            try {
                $stmt = $pdo->prepare($sql);
                $stmt->execute($data);
            } catch(PDOException $e) {
                $this->error = $e->getMessage();
            }
        } catch(PDOException $e) {
           $this->error =  $e->getMessage();
        }
    }

}
?>