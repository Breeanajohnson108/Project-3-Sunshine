<?php
/*
**************************************************
*                JOURNAL DATABASE                *
*          Created By Breeanna Johnson           *
**************************************************
*/

//Accessing the journal database using a try-catch block and handling errors
try
{
    $db = new PDO("sqlite:".__DIR__."/journal.db");
    //Creating a method on the pdo itself to throw any exception if there's a issue of any kind.
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} 
catch (Exception $e)
{
  //Creating a error message
  echo "Sorry!, you are unable to connect to the database.";
  echo $e->getMessage();
  //Stopping all codes from running using a exit command
  exit;
}

?>
