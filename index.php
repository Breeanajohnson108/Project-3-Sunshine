<?php
/*
**********************************************
*               Home Page                    *
*          By Breeanna Johnson               *
**********************************************
*/

//including header and functions php files
include 'inc/header.php';
include "inc/myFunctions.php";

//initializing an empty variable
$tag = '';

//Displaying error messages
if(isset($_GET['msg']))
{
  $error_message = trim(filter_input(INPUT_GET,'msg', FILTER_SANITIZE_STRING));
}

if(isset($_GET['tag']))
{
  $tag = trim(filter_input(INPUT_GET,'tag',FILTER_SANITIZE_NUMBER_INT));
  $tag_text = getTagText($tag);
}

if (isset($error_message)) 
{
    echo "<p class='message'>$error_message</p>";
}
?>

<section>
    <div class="container">
      <?php if($tag!='')
      {
        echo '<h2 class="tag">Journal Entries that are tagged: ' . $tag_text['tag'] . '</h2>';
      } 
      else 
      {
        echo '<h2 class="tag">My Journal Entries</h2>';
      }?>
        <div class="entry-list">
          <?php displayShortEntries($tag); ?>
        </div>
    </div>
</section>

<?php 
//including footer php file
include 'inc/footer.php'; 
?>
