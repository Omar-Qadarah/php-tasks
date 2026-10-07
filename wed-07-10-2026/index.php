<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PHP task 2</title>
</head>
<body>

  <?php
  echo "========================================================================== <br><br>";
  $str = "Twinkle, twinkle, little star.";
  $array = explode(", " , $str);
  var_dump($array);
  echo "<br><br> ========================================================================== <br><br>";
  $L = "f";
  switch ($L == "z") {
    case true:
      echo "a";
      break;
    default:
      echo ++$L;
  }

  echo "<br><br> ========================================================================== <br><br>";
  $str = "The brown fox.";
  echo $str . "<br> <br>";
  $word = "quick ";
  $nstr = substr_replace($str,$word,4,0);
  echo $nstr . "<br> <br>" . strtok($nstr," ");


  echo "<br><br> ========================================================================== <br><br>";
  $String = '0000657022.24';
 
  echo str_replace("0","", $String);

  echo "<br><br> ========================================================================== <br><br>";
  $sentence = "The quick brown fox jumps over the lazy dog---";
  echo rtrim($sentence,"-");

  echo "<br><br> ========================================================================== <br><br>";
  $str = "The quick brown fox jumps over the lazy dog";
  $sarray = explode(" ", $str);
  echo implode(" ",array_slice($sarray,0,5));
  echo "<br><br> ========================================================================== <br><br>";
  $an = "2,543.12";
  echo str_replace(",",0,$an);
  echo "<br><br> ========================================================================== <br><br>";
  echo "0, 1, ";
  $f = 0;
  $s = 1;
  for ($i=1; $i < 10; $i++) { 
    $th = $f + $s;
    $f = $s;
    $s = $th;
    echo $th;
    if($i !== 9){ echo  ", ";}
  }
  echo "<br><br> ========================================================================== <br><br>";
  $num = 1;
  for ($i=1; $i < 6; $i++) { 
    for ($j=1; $j <= $i; $j++) { 
      echo $num . " ";
      $num++;
    }
    echo "<br><br>";
  }

  echo "<br><br> ========================================================================== <br><br>";
  $s = 5;
  for ($i= 1; $i < 10; $i++) {
    $char = "A";
    if ($i < 6){
    for ($k= 1; $k <= $s-$i; $k++) {echo "_";}
    for ($j= 1; $j <= $i; $j++) {
      echo $char . " ";
      ++$char;
    }}else{
      for ($k= 1; $k <= $i-$s; $k++) {echo "_";}
      for ($j= 1; $j <= 10-$i; $j++){
        echo $char . " ";
        ++$char;
      }
    }
    echo "<br>";
  }
  echo "<br><br> ========================================================================== <br><br>";
  $year = 1904;
  if($year % 400 == 0 || $year % 4 == 0 && $year % 100 !== 0 ){
    echo $year . " is a leap year.";
  }else{echo $year . " is not a leap year.";}

  echo "<br><br> ========================================================================== <br><br>";
  $temp = 10;
  if ($temp<20){
    echo "Winter is here. 🥶";
  }else{echo "It is summertime! 😎";}
  echo "<br><br> ========================================================================== <br><br>";
  $f= 2;
  $s= 2;
  echo $f ." & ". $s . "<br><br >";
  if ($f === $s){echo ($s + $f) * 3;}else{echo "Unequal numbers";}
  echo "<br><br> ========================================================================== <br><br>";
  for ($i= 200; $i <= 250; $i++) {
    if ($i % 4 == 0){echo $i;}
    if ($i % 4 == 0 && $i !== 248){echo ", ";}
  }
  echo "<br><br> ========================================================================== <br><br>";
  $l = 11;
  $u = 20;
  $nums = [];
  while (count($nums)<10){
      $num = random_int($l, $u);
      if(!in_array($num,$nums)){
      array_push($nums,$num);
      }
  }
  echo implode(", ",$nums);
  echo "<br><br> ========================================================================== <br><br>";
  ?>
</body>
</html>