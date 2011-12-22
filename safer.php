<?php
require_once 'error.php';

class Html implements Str
{
    private $str, $type;

    function __construct($html)
    {
        $this->setType(self::HTML);
        $this->setStr($html);
    }

    function setType($type)
    {
        $this->type = $type;
    }

    function getType()
    {
        return $this->type;
    }
    function setStr($str)
    {
        $this->str = $str;
    }
    function getStr()
    {
        return $this->str;
    }
}

class Sql implements Str
{

    private $str,$type;

    function __construct($sql){
        $this->setType(self::SQL);
        $this->setStr($sql);
    }

    function setType($type)
    {
        $this->type = $type;
    }

    function getType()
    {
        return $this->type;
    }
    function setStr($str)
    {
        $this->str = $str;
    }
    function getStr()
    {
        return $this->str;
    }

}


interface Str
{
    const HTML = 0x00;
    const SQL  = 0x01;
    function getType();
    function getStr();
}

interface Safer_interface
{
    function safe_str();
}

abstract class Safer_abstract implements Safer_interface
{
    abstract function safe_str();
}

class Safer extends Safer_abstract
{
    protected $str,$type;

    function __construct(Str $s)
    {
        $this->str = $s->getStr();
        $this->type = $s->getType();
    }

    function safe_str()
    {
        if ($this->type == 0x01) {
            return $this->sql_safe($this->str);
        }
        else if($this->type == 0x00){
            return $this->html_safe($this->str);
        }
    }

    function sql_safe($str)
    {
        try{
            if($sql = "'" . mysql_real_escape_string($str) . "'")
                return $sql;
            else
                throw new Exception("$sql formart error");
        }
        catch(Exception $e)
        {
            trigger_error($e->getMessage());
        }
    }

    function html_safe($str)
    {
        return  htmlentities($str, ENT_QUOTES, 'UTF-8');
    }
}

//Usage:
$a = "<a href=''/> <script>alert('Hello');</script>";
$query_str = "test' AND 1=1;";
//$str = "SELECT * FROM test WHERE name=$safe\G";

$sql = new Sql($query_str);
$html = new Html($a);
$safer = new Safer($html);
var_dump($safer->safe_str());
