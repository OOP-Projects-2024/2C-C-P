
<?php

include_once "Common.php";

class Post extends Common{

    protected $pdo;

    public function __construct(\PDO $pdo){
        $this->pdo = $pdo;
    }

    public function postStudents(){
        //code for retrieving data on DB
        return "This is some student records added.";
    }

    public function postClasses(){
        //code for retrieving data on DB
        return "This is some classes records added.";
    }

    public function postFaculty(){
        //code for retrieving data on DB
        return "This is some faculty records added.";
    }


    public function postTaxi($body){
      if(is_array($body))
      {
        $body = (object) $body;
      }
      $result = $this->postData("taxi_tbl", ["id" => $body->id, "fname" => $body->fname, "lname" => $body->lname, "package" => $body->package], $this->pdo);
      if ($result ['code']==200) {
            $this->logger("Congrats", "POST", "Created a new taxi record.");
            return $this->generateResponse($result['data'], "success", "New taxi record has been successfully created.", 201);
        }
        $this->logger("Cyrynne and Phenelopy", "POST", $result['errmsg']);
        return $this->generateResponse(null, "failed", $result['errmsg'], $result['code']);
    }
}

?>
