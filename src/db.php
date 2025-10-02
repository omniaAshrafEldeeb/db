<?php
namespace Itracks\Backend0;


// interface qdl{
//     public function select($cols="*",$id=null);
// }

// interface mdl{
//     public function insert($d);
//     public function update($updae,$condition=null);
//     public function delete($condition=null);
// }
// implements qdl,mdl
class db  {

    private $sql;
    private $c;
    private $table;

    public function __construct($hostname="localhost",$username="root",$password="",$database="hello",$table="users")
    {
        $this->c=mysqli_connect($hostname,$username,$password,$database);
        $this->table=$table;
        // echo $table;
    }

    
    public function insert($data){
        $col="";
        $val="";
        foreach($data as $k => $v){
            $col.="`$k`, ";
            if(is_string($v)){
                $val.="'$v', ";
                continue;
            }
            $val.="$v, ";
        }
        $col=rtrim($col,", ");
        $val=rtrim($val,", ");
        // echo $this->table;
        $this->sql=["INSERT INTO  `$this->table` ($col) VALUES ($val)",0];
        // echo $this->sql;
        return $this;
    }


    public function update($updae,$condition=null){
        $up="";
        foreach($updae as $k=>$v){
            if(is_string($v)){
                $up.="`$k` = '$v', ";
            }
            else{
                $up.="`$k` = $v, ";
            }
        }
        $up=rtrim($up,", ");
        if($condition==null){
            $this->sql=["UPDATE `$this->table` SET $up",1];
        }else{
            $cond="";
            foreach($condition as $k =>$v){
                if(is_string($v)){
                    $cond="`$k` = '$v'";
                }
                else{
                    $cond="`$k` = $v";
                }
            }
            
            $this->sql=["UPDATE `$this->table` SET $up WHERE $cond",1];
        }
        // echo $this->sql;
        return $this;
    }



    public function delete($condition=null){
        if($condition!=null){
            foreach($condition as $k => $v){
                if(is_string($v)){
                    $con="`$k` = '$v'";
                }
                else{
                    $con="`$k` = $v";
                }
            }
            $this->sql=["DELETE FROM `$this->table` WHERE $con",2];
        }else{
            $this->sql=["DELETE FROM `$this->table`",2];
        }
        
        // echo $this->sql;
        return $this;
    }


    public function select($cols="*",$id=null){
        if($id==null){
            $this->sql=["SELECT $cols FROM $this->table"];
        }else{
            $this->sql=["SELECT $cols FROM `$this->table` WHERE ID IN ($id)"];
        }
        // echo"<pre>";
        // print_r($this->sql); 
        // echo $this->sql; 
        return $this;
    }

    public function where($col, $operator,$val){
        if(str_starts_with($val,"(")){
            $this->sql[0].=" WHERE `$col` $operator $val";
        }else{

            if(is_string($val)){
                $this->sql[0].=" WHERE `$col` $operator '$val'";
            }else{
            $this->sql[0].=" WHERE `$col` $operator $val";
            }
        }
        // echo $this->sql[0];
        return $this;
    }

    public function or($col, $operator,$val){
        if(str_starts_with($val,"(")){
            $this->sql[0].=" OR `$col` $operator $val";
        }else{

            if(is_string($val)){
                $this->sql[0].=" OR `$col` $operator '$val'";
            }else{
            $this->sql[0].=" OR `$col` $operator $val";
            }
        }
        // echo $this->sql[0];
        return $this;
    }

    public function AND($col, $operator,$val){
        if(str_starts_with($val,"(")){
            $this->sql[0].=" AND `$col` $operator $val";
        }else{

            if(is_string($val)){
                $this->sql[0].=" AND `$col` $operator '$val'";
            }else{
            $this->sql[0].=" AND `$col` $operator $val";
            }
        }
        // echo $this->sql[0];
        return $this;
    }



    public function INnerjoin($table,$pk,$fk){
        $this->sql[0].=" INNER JOIN `$table` ON $pk = $fk";
        return $this;
    }
    public function leftjoin($table,$pk,$fk){
        $this->sql[0].=" LEFT JOIN `$table` ON $pk = $fk";
        return $this;
    }
    public function rightJoin($table,$pk,$fk){
        $this->sql[0].=" RIGHT JOIN `$table` ON $pk = $fk";
        return $this;
    }



    public function excute(){
        // echo "hi";
        $r=mysqli_query($this->c,$this->sql[0]);
        if($r){
            if((mysqli_affected_rows($this->c))>0){
                if($this->sql[1]==0){
                    echo "sucessful added";
                }
                elseif($this->sql[1]==1){
                    echo "sucessful updated";
                }
                elseif($this->sql[1]==2){
                    echo "sucessful deleted";
                }
            }
        }
        // return mysqli_affected_rows($this->c);
    }

    public function fetech(){
        $r=mysqli_query($this->c,$this->sql[0]);
        $row=mysqli_fetch_all($r,MYSQLI_ASSOC);
        if(count($row)>0){
            // echo "okay";
            foreach($row as $v){
                foreach(array_keys($v) as $cn){
                    echo $cn."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";;
                }
                break;
            }
            echo "<br>";
            foreach($row as $v){
                
                foreach($v as $vv){
                    echo $vv. "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                }
                echo "<br>";
            }
        }
    }

}
?>