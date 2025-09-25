<?php 
echo "Class and Object <br>";
class car{ 
    //properties
    public $color;
    public $model;

    //methods
    public function cars($color,$model){
        $this->color=$color;
        $this->model=$model;
      
    }
    public function mes(){
          echo "MY car brand name is  ".$this->model." and the color is ".$this->color;
    }
}
//create an object
$mycar=new car();
$mycar->cars("black","BENZ");
$mycar->mes();

echo "<br>";
echo "<br>";
echo "Constructor and Destructor <br>";
//constructor and destructor
class Car1 {
    public $brand;

    public function __construct($brand) {
        $this->brand = $brand;
        echo "Car $this->brand created.<br>";
    }

    public function __destruct() {
        echo "Car $this->brand is destroyed.<br>";
    }
    
}

$car1 = new Car1("BMW");
$car2 = new Car1("Audi");
echo "<br>";
echo "Access Modifiers and Inheritance <br>";
//Access Modifiers and Inheritance
class bank{
    public $name;
    private $balance;
    protected $accountnumber;
    public function __construct($name,$accountnumber,$balance){
        $this->name=$name;
        $this->accountnumber=$accountnumber;
        $this->balance=$balance;
    }
    public function balance(){
       
        echo "Name:".$this->name."<br> Account Number:".$this->accountnumber."<br> Balance:".$this->balance."<br>";
    }
}

class bank1 extends bank{
    public function accountnumber(){
        echo "Name:".$this->name."<br> Account Number:".$this->accountnumber."<br>";
    }
}
$bank=new bank1("vicky",7854125478,50000);
$bank->balance();
$bank->accountnumber();
echo "<br>";
echo "<br>";
//Polymorphism
echo "polymorphism <br>";
class calculation{
    public $n1;
    public $n2;
    public function __construct($n1,$n2){
        $this->n1=$n1;
        $this->n2=$n2;
    }
    public function maths(){
        echo "The sum of two number is:".$this->n1+$this->n2."<br>";
    }
}
class calculation1 extends calculation{
    public function maths(){
        echo "The multiplication of two number is:".$this->n1*$this->n2."<br>";
    }
}
class calculation2 extends calculation{
    public function maths(){
        echo "The division of two number is:".$this->n1/$this->n2."<br>";
    }
}
$cal=new calculation(10,2);
$cal->maths();
$cal=new calculation1(10,2);
$cal->maths();
$cal=new calculation2(10,2);
$cal->maths();
echo "<br>";
echo "<br>";
echo "Encapsulation <br>";
//Encapsulation
class acdel{
    public $name;
    protected $logpass;
    protected $password="Welcome@123";
    protected $balance=50000;
    public $amount;
    public function __construct($name,$amount,$logpass){
        $this->name=$name;
        $this->amount=$amount;
        $this->logpass=$logpass;
    }
    public function setbalance(){
        if($this->logpass==$this->password){
            $this->balance+=$this->amount;
        }else{
            echo "Invalid Password";
        }
    }
}
class display extends acdel{
    public function getbalance(){
        echo "Name:".$this->name."<br> Balance:".$this->balance."<br>";
    }
}
$show=new display("vicky",5000,"Welcome@123");
$show->setbalance();
$show->getbalance();
echo "<br>";
echo "<br>";
echo "Traits <br>";
//Traits
trait message{
    public function msg($msg){
        echo "Your passord is invalied Please check your Password - ".$msg."<br>";        
    }
}
trait message1{
    public function msg1(){
        echo "Valid Password ";

    }

}
class user{
    use message,message1;
    private $password="Welcome@123";
    private $pass;
    public function __construct($pass){
        $this->pass=$pass;

    }
    public function checkpass(){
        if($this->pass == $this->password){
            $this->msg1();
        }else{
            $this->msg($this->pass);
        }
    }
}
$user=new user("Welcome@123");
$user1=new user("Welcome@12");
$user1->checkpass();
$user->checkpass();
echo "<br>";
echo "<br>";
//Namespace
echo "Namespace <br>";
require 'bank.php';
use bankapp\bank6;
$bank=new bank6();
$bank->showbalance("vicky",50000);
echo "<br>";
echo "<br>";
//Static property and method
echo "Static property and method <br>";
class school{
    public static $totalmark=0;
    public static function addmark($mark){
        self::$totalmark +=$mark;
    }
}
school::addmark(88);
school::addmark(54);
school::addmark(21);
echo "Total Mark:".school::$totalmark;
echo "<br>";
echo "<br>";
//  Abstract class
echo "Abstract class <br>";
abstract class bank_1{
    protected $balance;
    abstract public function deposit($amount);
    abstract public function withdraw($amount);
    public function showbalance(){
        echo "Balance:".$this->balance."<br>";
    }
}
class Account extends bank_1{
    public function __construct(){
        $this->balance=52111;
    }
    public function deposit($amount){

        $this->balance+=$amount;
        echo "Deposited:".$amount."Total Amount:".$this->balance."<br>";
    }
    public function withdraw($amount){
        if($amount > $this->balance){
            echo "Insufficient Balance<Br>";

        }else{
            $this->balance-=$amount;
            echo "Withdrawn:".$amount."Balance Amount:".$this->balance."<br>";
        }

    }
}
$acc=new Account();
$acc->deposit(100000);
$acc->withdraw(50000);
$acc->showbalance();
?>

