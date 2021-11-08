<?php
require_once "configDB.php";

$sql = "SELECT * FROM `".$_REQUEST['table']."`";        //Получаем все записи из выбранной таблицы
$query = $pdo->prepare($sql);
$query->execute();
$row=[];

do                                                      //Сохраняем в массив данные
{$row[count($row)] = $query->fetch(PDO::FETCH_OBJ);
if (!$row[count($row)-1]) {unset($row[count($row)-1]);break;}
}
while (true);
echo json_encode($row);                                 //Перевод в формат json
