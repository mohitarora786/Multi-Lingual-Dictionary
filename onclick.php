 <!DOCTYPE html>
 <html lang="en">
 <head>
  <title>Document</title>
  <link rel="stylesheet" href="styles1.css">
 </head>
 <body>
  <p class="name">TUSHAR'S DICTIONARY</p>
<div class="form">
 <form action="onclick.php">
  <input class="input" type="text" placeholder="Search Words" name="search">
  <input class="submitbutton" type="submit" value="Submit">
    </div>
</form>

 <?php
$servername = "localhost";
$username = "root";
$password = "tm1711tm";
error_reporting(0);

try {
  $conn = new PDO("mysql:host=$servername;dbname=dictionary", $username, $password);
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $Search = trim($_GET['search']," ");
  $num = 1;

  $sql = "select `Status`,`Word_ID` from `word` where `Searched_Word` = '$Search'";
        
  $result = $conn->query($sql);
  $count = $result->rowCount();

  if($count != 0){

  $result->setFetchMode(PDO::FETCH_ASSOC);
  $row = $result->fetch();
  $word_id=$row["Word_ID"];
  echo'<div class= "b">';
  echo '<div class = "BOX">';

  echo '<div class = "BOX_1">';

  if($row['Status']=='YES'){
    $sql1 = "select `Searched_Word`,`Syllable`,`Pronunciation`,`Scientific_name`,`Image` from `living` natural join (select * from `word` where `Word_ID`='$word_id') as `S_word`;";
    $result1 = $conn->query($sql1);
    $result1->setFetchMode(PDO::FETCH_ASSOC);
    $row1 = $result1->fetchALL();
      foreach($row1 as $living){

        echo '<div class="S_Word">';
        echo '<h1>'.$living['Searched_Word'].'</h1>';
        echo '<h3>('.$living['Syllable'].' - '.$living['Pronunciation'].' - '.$living['Scientific_name'].')</h3>';
        echo '</div>';

      }
    }else{

      echo '<div class="S_Word">';

        $sql1 = "select `Searched_Word`,`Syllable`,`Pronunciation`,`Word_ID` from `word` where `Word_ID`='$word_id';";
        $result1 = $conn->query($sql1);
        $result1->setFetchMode(PDO::FETCH_ASSOC);
        $row1 = $result1->fetchALL();
        foreach($row1 as $s_word){
          echo '<h1>'.$s_word['Searched_Word'].'</h1>';
          echo '<h3>('.$s_word['Syllable'].' - '.$s_word['Pronunciation'].')</h3>';
        }

      echo '</div>';

      }

      echo '</div>';

      echo '<div class = "BOX_2">';

      echo '<div class = "Meaning">';
        
        $sql2 = "select `Part_of_Speech_Tag`,`Description`,`Example`,`Meaning_ID` from `meaning` natural join (select * from `part_of_speech` natural join (select * from `tag_mean_rel` natural join (select * from `word_mean_rel` where `Word_ID`='$word_id') as `req_1`) as `req_2`)  as `Meaning_Word`;";
        $result2 = $conn->query($sql2);
        $result2->setFetchMode(PDO::FETCH_ASSOC);
         $row2 = $result2->fetchALL();
         foreach($row2 as $meaning){

          echo '<div class = "Loop">';
          echo '<p class="mean"><span class="bold">Meaning '.$num.':</span> ('.$meaning['Part_of_Speech_Tag'].')'.' '.$meaning['Description'].'</p>';
          echo '<p class="mean"><span class="bold">Example:</span> '.$meaning['Example'].'</p>';

          if($row['Status'] != 'YES'){
          $mean = $meaning['Meaning_ID'];
          echo '<p class="mean">Synonyms: ';
          $sql7 = "select `Searched_Word` as `Synonym` from `word` natural join (select (`Synonym_ID`) as `Word_ID`  from `Synonyms` where `Meaning_ID` = '$mean') as `Synonyms_word`;";
          $result7 = $conn->query($sql7);
          $count = $result7->rowCount() - 1;
          $result7->setFetchMode(PDO::FETCH_ASSOC);
          $row7 = $result7->fetchALL();
          foreach($row7 as $synonyms){
          echo $synonyms['Synonym'];
          if($count != 0){
          echo ' , ';
          $count = $count - 1;
          }
        }
        echo '</p>';

          echo '<p class="mean">Antonyms: ';

          $sql8 = "select `Searched_Word` as `Antonym` from word natural join (select (`Antonym_ID`) as `Word_ID`  from `antonyms` where `Meaning_ID` = '$mean') as `Antonyms_word`;";
          $result8 = $conn->query($sql8);
          $count = $result8->rowCount() - 1;
          $result8->setFetchMode(PDO::FETCH_ASSOC);
          $row8 = $result8->fetchALL();
          foreach($row8 as $antonyms){
          echo $antonyms['Antonym'];
          if($count != 0){
          echo ' , ';
          $count = $count - 1;
          }
        }
        echo '</p>';
        echo '</div>';
      }

        $num = $num + 1;
      }

      echo '</div>';

      echo '<div class = "Languages">';

      echo '<p class="lang">In other languages</p>';
          echo '<p class="lang">Hindi - ';

          $sql3 = "select `Word_ID`,`Lexeme` from `lexeme` natural join (select * from (select * from `interpretation` where `Word_ID`='$word_id') as `req_hindi` natural join (select * from `language` where `Language` = 'Hindi') as `Hindi`) as `Hindi_Words`;";
          $result3 = $conn->query($sql3);
          $count = $result3->rowCount() - 1;
          $result3->setFetchMode(PDO::FETCH_ASSOC);
          $row3 = $result3->fetchALL();
          foreach($row3 as $hindi){
          echo $hindi['Lexeme'];
          if($count != 0){
          echo ' , ';
          $count = $count - 1;
          }
        }
      echo '</p>';

          echo '<p class="lang">Marathi - ';
          $sql4 = "select `Word_ID`,`Lexeme` from `lexeme` natural join (select * from (select * from `interpretation` where `Word_ID`='$word_id') as `req_marathi` natural join (select * from `language` where `Language` = 'marathi') as `marathi`) as `marathi_Words`;";
          $result4 = $conn->query($sql4);
          $count = $result4->rowCount() - 1;
          $result4->setFetchMode(PDO::FETCH_ASSOC);
          $row4 = $result4->fetchALL();
          foreach($row4 as $marathi){
          echo $marathi['Lexeme'];
          if($count != 0){
          echo ' , ';
          $count = $count - 1;
          }
        }
      echo '</p>';

      echo '<p class="lang">Tamil - ';
          $sql5 = "select `Word_ID`,`Lexeme` from `lexeme` natural join (select * from (select * from `interpretation` where `Word_ID`='$word_id') as `req_tamil` natural join (select * from `language` where `Language` = 'tamil') as `tamil`) as `tamil_Words`;";
          $result5 = $conn->query($sql5);
          $count = $result5->rowCount() - 1;
          $result5->setFetchMode(PDO::FETCH_ASSOC);
          $row5 = $result5->fetchALL();
          foreach($row5 as $tamil){
          echo $tamil['Lexeme'];
          if($count != 0){
          echo ' , ';
          $count = $count - 1;
          }
        }
      echo '</p>';

      echo '<p class="lang">Telugu - ';
          $sql6 = "select `Word_ID`,`Lexeme` from `lexeme` natural join (select * from (select * from `interpretation` where `Word_ID`='$word_id') as `req_telugu` natural join (select * from `language` where `Language` = 'telugu') as `telugu`) as `telugu_Words`;";
          $result6 = $conn->query($sql6);
          $count = $result6->rowCount() - 1;
          $result6->setFetchMode(PDO::FETCH_ASSOC);
          $row6 = $result6->fetchALL();
          foreach($row6 as $telugu){
          echo $telugu['Lexeme'];
          if($count != 0){
          echo ' , ';
          $count = $count - 1;
          }
        }
      echo '</p>';

      echo '</div>';
      echo '</div>';
      

      if($row['Status']=='YES'){
        echo '<div class="image">';
        echo '<img  src="data:image/jpeg;base64,' . base64_encode($living['Image']).'">';
        echo '</div>';
      }

      echo '</div>';
      
}
else
{
  exit('<div class="error">Error: Word Not Found</div>');
}
}
 catch(PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}
?>
</div>
</body>
</html>