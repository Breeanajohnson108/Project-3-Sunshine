<?php

/*
**********************************************
*               RETRIEVING                   *
*          By Breeanna Johnson               *
**********************************************
*/

//Creating a function to retrieve journals from database
function getEntryShort($tag)
{
  //Including the access php file
  include ("access.php");

  //Joining all tables in the database
  $sql = 'SELECT DISTINCT entries.title, entries.date, entries.id FROM entries
    JOIN entries_tags ON entries.id = entries_tags.entry_id
    JOIN tags ON entries_tags.tag_id = tags.tag_id';

  $where = '';
  if ($tag != '') 
  { 
    $where = ' WHERE tags.tag_id = ?';
  }

  //Filtering to order by date with most recent on top
  $orderby = ' ORDER BY date DESC';

  try
  {
    $results = $db->prepare($sql . $where . $orderby);
    if($tag != '')
    {
      $results->bindValue(1,$tag);
    }
  } 
  catch (Exception $e)
  {
    //Creating a error message
    echo "Sorry!, Unable to retrieve journals.";
    exit;
  }
  $results->execute();

  $entries = $results->fetchAll();
  return $entries;
}

//Creating a function that gets specific details of a journal entry
function getDetailedEntry($id)
{
  //Including the access php file
  include("access.php");

  //Using a try-catch block to get the details and hanlde the errors
  try
  {
    $results = $db->prepare("SELECT * FROM entries
      WHERE id = ?");
    $results->bindValue(1,$id,PDO::PARAM_INT);
    $results->execute();
  } 
  catch (Exception $e)
  {
    //Creating a error message
    echo "Sorry!, Can't get specific details of journal entry.";
    exit;
  }

  //Returning the results
  $entries = $results->fetch(PDO::FETCH_ASSOC);
  return $entries;
}

//Creating a function to get recent tags from database
function getTags($id)
{
  //Including the access php file
  include("access.php");

  //Using a try-catch block to get tags and hanle any errors
  try 
  {
    $results = $db->prepare("SELECT tags.tag_id,tags.tag FROM tags
      JOIN entries_tags ON entries_tags.tag_id = tags.tag_id
      JOIN entries ON entries.id = entries_tags.entry_id
      WHERE id = ?");
    $results->bindValue(1,$id,PDO::PARAM_INT);
    $results->execute();
  } 
  catch (Exception $e)
  {
    //Creating a error message
    echo "Sorry!, no tags were founded.";
    exit;
  }

  //Returning results
  $tags = $results->fetchAll(PDO::FETCH_ASSOC);
  return $tags;
}

//Creating a function to get user's tag text
function getTagText($tag)
{
  include("access.php");

  //Using a try-catch block to get user's tag text and handle errors
  try 
  {
    $results = $db->prepare("SELECT tag FROM tags
      WHERE tag_id = ?");
    $results->bindValue(1,$tag,PDO::PARAM_INT);
    $results->execute();
  } 
  catch (Exception $e)
  {
    //Creating a error message
    echo "Sorry!, no user's tags text input was found.";
    exit;
  }

  //Returning all the results
  return $results->fetch(PDO::FETCH_ASSOC);
}

//Making a function to get entry id
function getEntryID($title)
{
  //Including the access php file
  include("access.php");

  //Using a try-catch block to get a entry id  and handling any mishaps
  try 
  {
    $results = $db->prepare("SELECT id FROM entries
      WHERE title = ?");
    $results->bindValue(1,$title,PDO::PARAM_STR);
    $results->execute();
  } 
  catch (Exception $e)
  {
    //Creating a error message
    echo "Sorry!, Unabl to retrieve entry IDs.";
    exit;
  }

  //Results are being returned
  return $results->fetch(PDO::FETCH_ASSOC);
}

//Making a function to get entry id
function getEntryID($title)
{
  //Including the access php file
  include("access.php");

  //Using a try-catch block to get a entry id and handling any mishaps
  try 
  {
    $results = $db->prepare("SELECT id FROM entries
      WHERE title = ?");
    $results->bindValue(1,$title,PDO::PARAM_STR);
    $results->execute();
  } 
  catch (Exception $e)
  {
    //Creating a error message
    echo "Sorry!, Unable to retrieve entry IDs.";
    exit;
  }

  //Results are being returned
  return $results->fetch(PDO::FETCH_ASSOC);
}

//Creating a function to get tag IDs from the database
function getTagID($tag)
{
  //Including the access php file
  include("access.php");

  //Creating try-catch block to get tag IDs and handling any exceptions.
  try 
  {
    $results = $db->prepare("SELECT tag_id FROM tags
      WHERE tag = ?");
    $results->bindValue(1,$tag,PDO::PARAM_STR);
    $results->execute();
  } 
  catch (Exception $e)
  {
    //Creating a error message
    echo "Sorry!, tags IDs weren't found.";
    exit;
  }

  //Returning the results
  return $results->fetch(PDO::FETCH_ASSOC);
}

//Creating a function that will get associated tags
function getTagAssociated($id)
{
  //Including the access php file
  include("access.php");

  //putting all tables together
  $sql = 'SELECT tag FROM tags
    JOIN entries_tags ON tags.tag_id = entries_tags.tag_id
    JOIN entries ON entries_tags.entry_id = entries.id
    WHERE entries.id = ?';

  //Making a try-catch block to get associated tags
  try
  {
    $results = $db->prepare($sql);
    $results->bindValue(1,$id,PDO::PARAM_STR);
    $results->execute();
  } 
  catch (Exception $e)
  {
    //Creating a error message
    echo "Sorry!, Can't get associated tags." . $e->getMessage();
  }

  //Returning results
  return $results->fetchAll(PDO::FETCH_ASSOC);
}

/*
**********************************************
*                Adding                      *
*          By Breeanna Johnson               *
**********************************************
*/

//Creating a function to add new entries
function addEntry($title, $date, $time_spent, $learned, $resources)
{
  //Including the access php file
  include("access.php");

  //Writing query to add tags to database
  $sql = 'INSERT INTO entries (title, date, time_spent, learned, resources) VALUES (?, ?, ?, ?, ?)';

  //Using a try-catch block to add info
  try
  {
    $results = $db->prepare($sql);
    $results->bindValue(1,$title,PDO::PARAM_STR);
    $results->bindValue(2,$date,PDO::PARAM_STR);
    $results->bindValue(3,$time_spent,PDO::PARAM_STR);
    $results->bindValue(4,$learned,PDO::PARAM_STR);
    $results->bindValue(5,$resources,PDO::PARAM_STR);
    $results->execute();
  } 
  catch (Exception $e)
  {
    //Creating a error message
    echo "Sorry!, Can't add journal entry." . $e->getMessage();
    exit;
  }

  //Returning results
  return $results;
}

//Creating a funtion to add new tags
function addTag($tag_name)
{
  //Including the access php file
  include("access.php");

  //Writing query to add tags to database
  $sql = 'INSERT INTO tags(tag) VALUES(?)';

  //Using try-catch block to add new tags 
  try
  {
    $results = $db->prepare($sql);
    $results->bindValue(1,$tag_name,PDO::PARAM_STR);
    $results->execute();
  } 
  catch (Exception $e)
  {
    //Creating a error message
    echo "Sorry!, Can't add new tag." . $e->getMessage();
  }

  //Returning results
  return $results;
}

//Creating a function to add associated tags
function addTagAssociation($tag_id, $entry_id)
{
  //Including the access php file
  include("access.php");

  //Writing query to add associated tags to database
  $sql = 'INSERT INTO entries_tags(tag_id, entry_id) VALUES(?,?)';

  //using try-catch block to add associated tags
  try
  {
    $results = $db->prepare($sql);
    $results->bindValue(1,$tag_id,PDO::PARAM_INT);
    $results->bindValue(2,$entry_id,PDO::PARAM_INT);
    $results->execute();
  } 
  catch (Exception $e)
  {
    //Creating a error message
    echo "Sorry!, unable to add associate tags." . $e->getMessage();
  }

  //Returning results
  return $results;
}

/*
**********************************************
*                Editing                     *
*          By Breeanna Johnson               *
**********************************************
*/

//Creating a function to edit entries
function editEntry($title, $date, $time_spent, $learned, $resources, $id)
{
  //Including the access php file
  include("access.php");

  //Writing query to update changes of entry to database
  $sql = 'UPDATE entries SET title = ?, date = ?, time_spent = ?, learned = ?, resources = ? WHERE id = ?';

  //Using a try-catch block to update entries
  try
  {
    $results = $db->prepare($sql);
    $results->bindValue(1,$title,PDO::PARAM_STR);
    $results->bindValue(2,$date,PDO::PARAM_STR);
    $results->bindValue(3,$time_spent,PDO::PARAM_STR);
    $results->bindValue(4,$learned,PDO::PARAM_STR);
    $results->bindValue(5,$resources,PDO::PARAM_STR);
    $results->bindValue(6,$id,PDO::PARAM_INT);
    $results->execute();
  } 
  catch (Exception $e)
  {
    //Creating a error message
    echo "Sorry!, Can't update entry." . $e->getMessage();
    exit;
  }

    //Returning results
    return $results;
}

//Creating a function to edit all tags
function editTags($tag_list, $id)
{
  //Including the access php file
  include("access.php");

    // Converting $tag_list into an array
    $tag_list = array_filter(explode(',',$tag_list),'strlen');

    // Getting list of tags associated with entry using a foreach loop
    $associated_tags = getTagAssociated($id);
  for($i=0;$i<(count($associated_tags));$i++)
  {
    $associated_tags_array[] = $associated_tags[$i]['tag'];
  }

    //Checking to see if all entry tags are all associated using a foreach loop
    foreach($associated_tags_array AS $key => $i)
  {
    if(!in_array($i,$tag_list))
    {
            // Unassociating the tags
            $tag_id = getTagID($i);
            removeTag($tag_id['tag_id'],$id);
    }
  }

    // Checking to see if added tags are already connected with the entries
    foreach($tag_list AS $key => $i)
  {
    if(!in_array($i,$associated_tags_array))
    {
            // Checingk to see if tags have been created
            $tagChecked = checkTag($i);

            // Adding tag if there is no tags
            $tag_name = trim(strtolower($i));
      if($tagChecked==0)
      {
        addTag($tag_name);
        $tag_id = getTagID($tag_name);
      }

            // Applying tag to entry if it isn't
            addTagAssociation($tag_id['tag_id'],$id);
    }
  }

  //Returning results true
  return true;
}

/*
**********************************************
*                Removing                    *
*          By Breeanna Johnson               *
**********************************************
*/

//Making a function that will remove any associated tag
function removeTagAssociation($entry_id)
{
  //Including the access php file
  include("access.php");

  //Writing query to remove associated tag from database
  $sql = 'DELETE FROM entries_tags WHERE entry_id = ?';

  //Removing associated tags using try-catch block and handling errors
  try
  {
    $results = $db->prepare($sql);
    $results->bindValue(1,$entry_id,PDO::PARAM_INT);
    $results->execute();
  } 
  catch (Exception $e)
  {
    //Creating a error message
    echo "Sorry!, Can't remove associated tag." . $e->getMessage();
  }

  //Returning results
  return $results;
}

//Creating a function that will delete entry
function deleteEntry($id)
{
  //Including the access php file
  include("access.php");

  //Writing query to remove tag from database
  $sql = 'DELETE FROM entries WHERE id=?';

  //Removing tags from database using try-catch block
  try
  {
    $results = $db->prepare($sql);
    $results->bindValue(1,$id,PDO::PARAM_INT);
    $results->execute();
  } 
  catch (Exception $e)
  {
    //Creating a error message
    echo "Sorry!, Can't remove tags." . $e->getMessage() . "<br />";
    return false;
  }

  if($results->rowCount() >0)
  {
    return true;
  } 
  else
  {
    return false;
  }
}

//Creating a function that will delete tags
function removeTag($tag_id, $entry_id)
{
  //Including the access php file
  include("access.php");

  //Writing query to remove tag from database
  $sql = 'DELETE FROM entries_tags WHERE tag_id = ? AND entry_id = ?';

  //Removing tags from database using try-catch block
  try
  {
    $results = $db->prepare($sql);
    $results->bindValue(1,$tag_id,PDO::PARAM_INT);
    $results->bindValue(2,$entry_id,PDO::PARAM_INT);
    $results->execute();
  } 
  catch (Exception $e)
  {
    //Creating a error message
    echo "Sorry!, Can't remove tags." . $e->getMessage();
  }

  //Returning results
  return $results;
}

/*
**********************************************
*                 Misc                       *
*          By Breeanna Johnson               *
**********************************************
*/

//Making a function to show entries
function displayShortEntries($tag = NULL)
{
  //Creating a variable to hold the function getEntryShort
  $entryShort = getEntryShort($tag);

  //Displaying journals using foreach loop
  foreach ($entryShort as $key) 
  {
    $tags = getTags($key['id']);
    echo "<article>";
    echo "<h2><a href=\"detail.php?id=" . $key['id'] . "\">" . $key['title'] . "</a></h2>";
    echo "<time datetime=\"" . $key['date'] . "\">" . date('F j, Y',strtotime($key['date'])) . "</time>";
    echo "<div>";
    if(!empty($tags))
    {
        foreach($tags as $tag)
        {
            echo '<a href="index.php?tag=' . $tag['tag_id'] . "\" class=\"journal-tag\">";
            echo $tag['tag'];
            echo '</a>';
        }
    }
    echo "</div>";
    echo "</article>";
  }

}


//Creating a function to check on tags
function checkTag($i)
{
  //Including the access php file
  include("access.php");

  //Creating a variable to hold tag names
  $tag_name = trim($i);

  //Writing query to check on tags from database
  $sql = 'SELECT * FROM tags WHERE tag = ?';

  //Checking on tags using try-catch block
  try
  {
    $results = $db->prepare($sql);
    $results->bindValue(1,$tag_name,PDO::PARAM_STR);
    $results->execute();
  } 
  catch (Exception $e)
  {
    //Creating a error message
    echo "Sorry!, Can't check on tags." . $e->getMessage();
  }

  //Returning results
  return $results->rowCount();
}


?>
