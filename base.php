<?php
session_start();
date_default_timezone_set('Asia/Taipei');

class DB{
    private $dsn="mysql:host=localhost;charset=utf8;dbname=web02";
    private $pdo;
    public $table;

    function __construct($table){
        $this->table=$table;
        $this->pdo=new PDO($this->dsn,'root','');
        
    }

    function all(...$arg){
        $sql="SELECT * FROM $this->table ";
        switch(count($arg)){
            case 1:
                if(is_array($arg[0])){
                    foreach($arg[0] as $key => $val){
                        $tmp[]="`$key`='$val'";
                    }
                    $sql .= " WHERE " . implode(" && ",$tmp);
                }else{
                    $sql .= $arg[0];
                }
            break;
            case 2:
                foreach($arg[0] as $key => $val){
                    $tmp[]="`$key`='$val'";
                }
                $sql .= " WHERE " . implode(" && ",$tmp);
                $sql .= $arg[1];

            break;
        
        }
//   echo $sql;
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    function find($id){
        $sql="SELECT * FROM $this->table ";

        if(is_array($id)){
            foreach($id as $key => $val){
                $tmp[]="`$key`='$val'";
            }
            $sql .= " WHERE " . implode(" && ",$tmp);
        }else{
            $sql .= " WHERE `id`='$id'";
        }
        // echo $sql;
        return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    }
    function save($array){
        if(isset($array['id'])){
            foreach($array as $key => $val){
                $tmp[]="`$key`='$val'";
            }

            $sql="UPDATE $this->table SET ".join(",",$tmp)." WHERE `id`='{$array['id']}'";

        }else{

            $sql="INSERT INTO $this->table (`".join("`,`",array_keys($array))."`)";
            $sql .= " VALUES('".join("','",$array)."')";

        }
//   echo $sql;
        return $this->pdo->exec($sql);

    }
    function del($id){
        $sql="DELETE FROM $this->table ";

        if(is_array($id)){
            foreach($arg[0] as $key => $val){
                $tmp[]="`$key`='$val'";
            }
            $sql .= " WHERE " . implode(" && ",$tmp);
        }else{
            $sql .= " WHERE `id`='$id'";
        }
//   echo $sql;
        return $this->pdo->exec($sql);
    }
    function q($sql){
        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    function math($math,$col,...$arg){
        $sql="SELECT $math($col) FROM $this->table ";
        switch(count($arg)){
            case 1:
                if(is_array($arg[0])){
                    foreach($arg[0] as $key => $val){
                        $tmp[]="`$key`='$val'";
                    }
                    $sql .= " WHERE " . implode(" && ",$tmp);
                }else{
                    $sql .= $arg[0];
                }
            break;
            case 2:
                foreach($arg[0] as $key => $val){
                    $tmp[]="`$key`='$val'";
                }
                $sql .= " WHERE " . implode(" && ",$tmp);
                $sql .= $arg[1];

            break;
        
        }
           echo $sql;
     return $this->pdo->query($sql)->fetchColumn();
    }


      
}

function to($url){
    header("location:".$url);
}

function dd($array){
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}

$User=new DB('user');
$News=new DB('news');
$View=new DB('view');
$Que=new DB('que');
$Log=new DB('log');


if(!isset($_SESSION['view'])){
    
    if($View->math('count','*',['date'=>date("Y-m-d")])>0){
        $view=$View->find(['date'=>date("Y-m-d")]);
        //$view['total']++
        //$view['total']=$view['total']+1;
        $view['total']+=1;
        $View->save($view);
        $_SESSION['view']=$view['total'];
    }else{
        $View->save(['date'=>date("Y-m-d"),'total'=>1]);
        $_SESSION['view']=1;
    }
}

// $qq=$View->math('sum','total');
// echo"<br>";
// echo $qq;

//  $qq=$View->math('count','*',['date'=>date("Y-m-d")]);
//  echo '<br>';
//  echo $qq ;
?>