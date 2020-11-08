<?php
    session_save_path("./session");
    session_start();
    include("common.php");

    if(isset($_SESSION['UserData']['username'])) {
        echo "Currently logged in as: " . $_SESSION['UserData']['username'];
    } else {
        header("location: login.php");
    }
?>

<!DOCTYPE html>
<html>
<head>
<link href="gamestyles.css" type="text/css" rel="stylesheet">
<title>Hangman Game</title>
</head>
<?php
    $words = array();
    $numwords = 0;

    function printPage($wordtemplate, $which, $guessed, $wrong, $hintsleft) {
      $numleft = 6 - $wrong;
      $img = $wrong+1;
      echo <<<ENDPAGE
      <!DOCTYPE html>
      <html>
        <head>
          <title>Hangman</title>
        </head>
      </html>
      <body>
        <h1>Hangman Game</h1>
        <p><strong>Word to guess: $wordtemplate</strong></p>
        <p>Letters used in guesses so far: $guessed</p>
        <p>Wrong guesses left: $numleft</p>

        <img width="100px" src="./images/$img.png" alt="hangman">
      
        <form method="post" action="game.php">
          <input type="hidden" name="wrong" value="$wrong">
          <input type="hidden" name="lettersguessed" value="$guessed">
          <input type="hidden" name="word" value="$which">
          <input type="hidden" name="hintsleft" value="$hintsleft">
          <fieldset>
            <legend>GUESS A LETTER</legend>
            <input type="text" name="letter" autofocus maxlength="1" pattern="[A-Za-z]">
            <button type="submit" name="guess">Guess</button>
            <button type="submit" name="hint">Hint</button>
            <p>Hints Remaining: $hintsleft</p>
          </fieldset>
        </form>
      </body>
      ENDPAGE;
    }
    
    function loseGame($word) {
        echo <<<EOC
      <!DOCTYPE html>
      <html>
       <head>
          <title>Hangman</title>
        </head>
        <body>
          <h1>You lost!</h1>
          <p>The word you were trying to guess was <em>$word</em>. Your losses have been updated.</p>
          <img width="100px" src="./images/7.png" alt="hangman" />
        </body>
      </html>
      EOC;
      addLoss($_SESSION['UserData']['username']);
    }
      
    function winGame($word) {
        echo <<<EOC
      <!DOCTYPE html>
      <html>
        <head>
          <title>Hangman</title>
        </head>
        <body>
          <h1>You win!</h1>
          <p>Congratulations! You guessed that the word was <em>$word</em>. Your wins have been updated.</p>
        </body>
      </html>
      EOC;
      addWin($_SESSION['UserData']['username']);
    }

    function selectlibrary(){
        global $words;
        global $numwords;
        
        $filename= './words.txt';
        $input = fopen($filename, "r");
        
        while (true) {
            $str = fgets($input);
            if (!$str) break;
            $words[] = rtrim($str);
            $numwords++;
        }
      
        fclose($input); 
    }

    function start(){
        global $words;
        global $numwords;
        global $level;
        global $mode;

        $level=$_POST["difficulty"];
        $mode=$_POST["mode"];
        $wrong=0;
        $hintsleft = $_POST['hints'];

        
        if ($mode=="a"){
            if ($level==1){
              $which = rand(0,49);
            }
            if ($level==2){
              $which = rand(50,99);
            }
            if ($level==3){
              $which = rand(100,154);
            }
            if ($level==4){
              $which = rand(155,367);
            }
        }
        if ($mode=="b"){
          if ($level==1){
            $which = rand(368,378);
          }
          if ($level==2){
            $which = rand(379,391);
          }
          if ($level==3){
            $which = rand(392,402);
          }
          if ($level==4){
            $which = rand(403,416);
          }
        } 
        $word =  $words[$which];
        $len = strlen($word);
        $temp1 = preg_replace('/\s/','--',$word);
        $pattern = '/[a-z]/i';
        $wordtemplate = preg_replace($pattern, '_ ',$temp1);
        printPage($wordtemplate, $which, "", $wrong, $hintsleft);
    }

    function matchLetters($word, $guessedLetters) {
        
        global $words;
        
        $len = strlen($word);
        $temp = preg_replace('/\s/','--',$word);
        $pattern = '/[a-z]/i';
        $wordtemplate = preg_replace($pattern, '_ ',$temp);
      
        for ($i = 0; $i < $len; $i++) {
          $charguess = $word[$i];
          if (strstr($guessedLetters, $charguess)) {
            $pos = 2 * $i;
            $wordtemplate[$pos] = $charguess;
          }
        }
      
        return $wordtemplate;
    }
      
    function playerGuess() {
        global $words;
        $hintsleft = $_POST["hintsleft"];
        $which = $_POST["word"];
        $word  = $words[$which];
        $wrong = $_POST["wrong"];
        $lettersguessed = $_POST["lettersguessed"];
        $guess = $_POST["letter"];
        $wordtemplate = matchLetters($word, $lettersguessed);

        if ($guess==""){
          printPage($wordtemplate, $which, $lettersguessed, $wrong, $hintsleft);
        }
        else{
          $letter = strtoupper($guess[0]);

          if (!strstr($lettersguessed, $letter)){
            if(!strstr($word, $letter)) {
              $wrong++;
            }
            $lettersguessed = $lettersguessed . " " . $letter;
          }
  
          $wordtemplate = matchLetters($word, $lettersguessed);
          if (!strstr($wordtemplate, "_")) {
               winGame($word);
          } else if ($wrong >= 6) {
            loseGame($word);
          } else {
            printPage($wordtemplate, $which, $lettersguessed, $wrong, $hintsleft);
          }
        }
    }

    function playerHint() {
      global $words;
      $hintsleft = $_POST["hintsleft"];
      $which = $_POST["word"];
      $word  = $words[$which];
      $temp = $word;
      $wrong = $_POST["wrong"];
      $lettersguessed = $_POST["lettersguessed"];
      $wordtemplate = matchLetters($word, $lettersguessed);
      if ($hintsleft>=1){
        if (empty($lettersguessed)){
          $len = strlen($word);
          $i = rand(0,$len-1);
          echo "Your hint is the letter: " . $word[$i];
          $hintsleft--;
        }
        else{
        $pattern = "/[" . $lettersguessed . "]/";
        $hintcache = preg_replace($pattern,'',$word);
        $len = strlen($hintcache);
        $i = rand(0,$len-1);
        echo "Your hint is the letter: " . $hintcache[$i];
        $hintsleft--;
        }
      }
      else{
        echo "You have no hints left!";
      }
      printPage($wordtemplate, $which, $lettersguessed, $wrong, $hintsleft);
    }
    selectlibrary();

    if(isset($_POST['guess'])){
        playerGuess();
    }
    else if(isset($_POST['newgame'])){
        start();
    }
    else if(isset($_POST['hint'])){
        playerHint();
    }
    
?>
<?php
  include("config.php");
?>

<body>
</body>
</html>
