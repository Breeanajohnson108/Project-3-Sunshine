<?php
/*
**********************************************
*         Creating New Entry Page            *
*          By Breeanna Johnson               *
**********************************************
*/

include 'inc/header.php';
include "inc/myFunctions.php";

//Creating empty variables

$learned = " ";
$date = " ";
$resources = " ";
$title = " ";
$time = " ";
$tags = " ";

//Checking to see if request method is post
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
  $title = trim(filter_input(INPUT_POST,'title',FILTER_SANITIZE_STRING));
  $date = trim(filter_input(INPUT_POST,'date',FILTER_SANITIZE_STRING));
  $timeSpent = filter_input(INPUT_POST,'timeSpent',FILTER_SANITIZE_STRING);
  $whatLearned = trim(filter_input(INPUT_POST,'whatILearned',FILTER_SANITIZE_STRING));
  $resources = trim(filter_input(INPUT_POST,'ResourcesToRemember',FILTER_SANITIZE_STRING));
  $tag_list = trim(filter_input(INPUT_POST,'tags',FILTER_SANITIZE_STRING));

  //Checking to see if variables are empty, and if everything is correct add entry to database and return to the home screen
  if (empty($title) || empty($date) || empty($timeSpent) || empty($whatLearned) || empty($resources) || empty($tag_list)) 
  {
    $error_message = 'Please fill in all fields';
  } 
  else
  {
    //Running the addEntry function which adds a entry to the database
    if(addEntry($title, $date, $timeSpent, $whatLearned, $resources))
    {
      //sending back to home page
      $id = getEntryID($title);
      if(editTags($tag_list,$id['id']))
      {
        header('Location: index.php');
        exit;
      }
    } 
    else 
    {
      $error_message = 'Could not add new entry';
    }
  }
}
?>

  <section>
    <div class="container">
      <div class="new-entry">
        <h2>New Entry</h2>
          <?php
            if (isset($error_message))
            {
              echo "<p class='message'>$error_message</p>";
            }
          ?>
          <form method="post" action="createEntry.php">
            <label for="title"> Title</label>
            <input id="title" type="text" name="title" value="<?php echo htmlspecialchars($title); ?>">
            <br>

            <label for="date">Date</label>
            <input id="date" type="date" name="date" value="<?php echo htmlspecialchars($date); ?>">
            <br>

            <label for="time-spent"> Time Spent</label>
            <input id="time-spent" type="text" name="timeSpent" value="<?php echo htmlspecialchars($timeSpent); ?>">
            <br>

            <label for="what-i-learned">What I Learned</label>
            <textarea id="what-i-learned" rows="5" name="whatILearned"><?php echo htmlspecialchars($whatLearned); ?></textarea>
            <label for="resources-to-remember">Resources to Remember</label>
            <textarea id="resources-to-remember" rows="5" name="ResourcesToRemember"><?php echo htmlspecialchars($resources); ?></textarea>
                        
            <label for="tags">Tags</label>
            <input id="tags" type="text" name="tags" value="<?php echo $tag_list;?>">
            <br>

            <input type="submit" value="Publish Entry" class="button">
              <a href="index.php" class="button button-secondary">Cancel</a>
          </form>
        </div>
      </div>
    </section>
    <?php 
    //including footer php file
    include 'inc/footer.php'; 
    ?>
