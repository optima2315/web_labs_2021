<?php
session_start();
require 'configDB.php';
if (!isset($_POST['data'])) { echo '<h3>Your data equals null.</h3>';}          //Проверка стоит перед отправкой, при корректной последовательности вызовов сработать не должно
                                                                                //В каестве примера добавлена работа с массивом сессий
if (!isset($_SESSION['client_id']))
{$id=GetClientId();}
else {$id=$_SESSION['client_id'];}

if ($id==0)                                                                     //Если пользователь впервые что-то отправил, занесем его в таблицу клиентов
{
    $sql = "INSERT INTO clients (surname, email";
    $len=2;
    if (!empty($_POST['data']['phone']))
    {$sql .=',phone'; $len++;}
    $sql .=") VALUES ('".implode("', '",array_slice($_POST['data'],0,$len))."')";
    $query = $pdo->prepare($sql);
    $query->execute();
    $id=GetClientId();                                                          //Теперь пользователь в БД
    $_SESSION['client_id']=$id;
}


if (isset($_POST['data']['comment'])) {$len = array_search('comment', array_keys($_POST['data']));}
else {$len = array_search('name', array_keys($_POST['data']));}

$sql = "INSERT INTO suggestions (client_id,";                                    //Занесём данные в таблицу предложений
$sql.= implode(',',array_slice(array_keys($_POST['data']),$len)).") VALUES ('";
$sql.= "$id','".implode("','",array_slice($_POST['data'],$len))."')";
$query = $pdo->prepare($sql);
$query->execute();
$tmp=$query->fetch(PDO::FETCH_OBJ);                                              //test->clean up later


$str = "<div class='row'><div class='col s12 m6'><h4>Thank you for your suggestion!</h4><h5></h5> Your data:</h5></div><div class='col s12 m5 offset-m1'>";
$str.="<br><ul class='collection with-header z-depth-4'> <li class='collection-header'><h5>Suggestion</h5></li>";
foreach ($_REQUEST['data'] as $k=>$v)
{
    $str .="<li class='collection-item'><i class='material-icons left'>check</i>".$k.": ".$v."</li>";
}
$str .="</ul></div></div>";




echo $str;

exit();
function GetClientId(): int
{
    require 'configDB.php';
  if (!isset($_REQUEST['data']['surname'])) {return $id=0;}
    $sql = "SELECT id FROM clients WHERE surname= '".$_REQUEST['data']['surname']."'";
    $query = $pdo->prepare($sql);
    $query->execute();
    $id=$query->fetch(PDO::FETCH_OBJ);
    return $id->id;
}
