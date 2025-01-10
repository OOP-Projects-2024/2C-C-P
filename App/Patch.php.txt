


<?php
class Patch{

    protected $pdo;

    public function __construct(\PDO $pdo){
        $this->pdo = $pdo;
    }


    public function patchTaxi($body, $id){
        $values = [];
        $errmsg = "";
        $code = 0;


        foreach($body as $value){
            array_push($values, $value);
        }

        array_push($values, $id);
        
        try{
            $sqlString = "UPDATE taxi_tbl SET fname=?, lname=?, package=?, isdeleted=? WHERE id = ?";
            $sql = $this->pdo->prepare($sqlString);
            $sql->execute($values);
          
            $code = 200;
            $data = null;

            return array("data"=>$data, "code"=>$code);
        }
        catch(\PDOException $e){
            $errmsg = $e->getMessage();
            $code = 400;
        }

        
        return array("errmsg"=>$errmsg, "code"=>$code);

    }

    public function archiveTaxi($id){
        
        $errmsg = "";
        $code = 0;
        
        try{
            $sqlString = "UPDATE taxi_tbl SET isdeleted=1 WHERE id = ?";
            $sql = $this->pdo->prepare($sqlString);
            $sql->execute([$id]);

            $code = 200;
            $data = null;

            return array("data"=>$data, "code"=>$code);
        }
        catch(\PDOException $e){
            $errmsg = $e->getMessage();
            $code = 400;
        }

        
        return array("errmsg"=>$errmsg, "code"=>$code);

    }

    public function patchAccount($body, $id){
        $values = [];
        $errmsg = "";
        $code = 0;


        foreach($body as $value){
            array_push($values, $value);
        }

        array_push($values, $id);
        
        try{
            $sqlString = "UPDATE accounts_tbl SET username=?, password=?, isdeleted=? WHERE id = ?";
            $sql = $this->pdo->prepare($sqlString);
            $sql->execute($values);

            $code = 200;
            $data = null;

            return array("data"=>$data, "code"=>$code);
        }
        catch(\PDOException $e){
            $errmsg = $e->getMessage();
            $code = 400;
        }

        
        return array("errmsg"=>$errmsg, "code"=>$code);

    }

    public function archiveAccount($id){
        
        $errmsg = "";
        $code = 0;
        
        try{
            $sqlString = "UPDATE accounts_tbl SET isdeleted=1 WHERE id = ?";
            $sql = $this->pdo->prepare($sqlString);
            $sql->execute([$id]);

            $code = 200;
            $data = null;

            return array("data"=>$data, "code"=>$code);
        }
        catch(\PDOException $e){
            $errmsg = $e->getMessage();
            $code = 400;
        }

        
        return array("errmsg"=>$errmsg, "code"=>$code);

    }

    
}

?>
