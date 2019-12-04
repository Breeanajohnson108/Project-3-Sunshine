<?php

ini_set("display_errors", "on");

include 'inc/header.php';
include "inc/projectFuntcions.php";

$id = trim(filter_input(INPUT_GET,'id', FILTER_SANITIZE_NUMBER_INT));
$entry = getDetailedEntry($id);
$tags = getTags($id);

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
  $title = trim(filter_input(INPUT_POST,'title',FILTER_SANITIZE_STRING));
  $date = trim(filter_input(INPUT_POST,'date',FILTER_SANITIZE_STRING));
  $timeSpent = filter_input(INPUT_POST,'timeSpent',FILTER_SANITIZE_STRING);
  $whatLearned = trim(filter_input(INPUT_POST,'whatILearned',FILTER_SANITIZE_STRING));
  $resources = trim(filter_input(INPUT_POST,'ResourcesToRemember',FILTER_SANITIZE_STRING));
  $tag_list = trim(filter_input(INPUT_POST,'tags',FILTER_SANITIZE_STRING));

  if (empty($title) || empty($date) || empty($timeSpent) || empty($whatLearned) || empty($resources) || empty($tag_list)) 
  {
      $error_message = 'Fill in the required fields: Title, Date, Time Spent, What I Learned, Resources To Remember, Tags. Thank You';
  } 
  else
  {
    if(editEntry($title,$date,$timeSpent,$whatLearned,$resources,$id))
    {
      if(editTags($tag_list,$id))
      {
        header('Location: detail.php?id='.$id);
        exit;
      } 
      else 
      {
        $error_message = 'Tags are not updated. Please update tags.';
      }
    } 
    else 
    {
      $error_message = 'Entry was not updated. Please update entry.';
    }
  }
} 
else 
{
  $title = $entry['title'];
  $date = $entry['date'];
  $timeSpent = $entry['time_spent'];
  $whatLearned = $entry['learned'];
  $resources = $entry['resources'];
  $tag_list = '';

  foreach($tags as $key => $element) 
  {
    $tag_list .= strtolower($element['tag']);
    end($tags);
    if (!($key === key($tags)))
    {
      $tag_list .= ',';
    }
  }
}
?>

<section>
<div class="container">
    <div class="edit-entry">
        <h2>Edit Entry</h2>
            <p>*All fields are required.*</p>
                <?php
                    if (isset($error_message))
                    {
                      echo "<p class='message'>$error_message</p>";
                    }
                ?>

                <form method="post" action="editEntry.php?id=<?php echo $id ?>">
                    <input type="hidden" name="id" id="id" value="<?php echo $id ?>" />

                    <label for="title"> Title</label>
                    <input id="title" type="text" name="title" value="<?php echo htmlspecialchars_decode($title); ?>">
                    <br>

                    <label for="date">Date</label>
                    <input id="date" type="date" name="date" value="<?php echo htmlspecialchars_decode($date); ?>">
                    <br>

                    <label for="time-spent"> Time Spent</label>
                    <input id="time-spent" type="text" name="timeSpent" value="<?php echo htmlspecialchars_decode($timeSpent); ?>">
                    <br>

                    <label for="what-i-learned">What I Learned</label>
                    <textarea id="what-i-learned" rows="5" name="whatILearned"><?php echo htmlspecialchars_decode($whatLearned); ?></textarea>

                    <label for="resources-to-remember">Resources to Remember</label>
                    <textarea id="resources-to-remember" rows="5" name="ResourcesToRemember"><?php echo htmlspecialchars_decode($resources); ?></textarea>

                    <label for="tags">Tags</label>
                    <input id="tags" type="text" name="tags" value="<?php echo $tag_list;?>">
                    <br>

                    <input type="submit" value="Publish Entry" class="button">
                    <a href="#" class="button button-secondary">Cancel</a>
                </form>
    </div>
</div>
</section>

<?php include 'inc/footer.php'; ?>