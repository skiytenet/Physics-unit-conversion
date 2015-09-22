<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Basår - Enhetsomvandling</title>
    </head>
    <body>


<style>
table, input {
  font-size:16pt;
}
</style>

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);


mb_internal_encoding("UTF-8");


$types = array("1" => "", "2" => "²", "3" => "³");

$prefix = array("1" => "p", "2"=>"n", "3" => "\xC2\xB5", "4" => "m", "5"=>"c",
"6" => "d", "7" => "h", "8" => "k", "9" => "M", "10" => "G", "11" => "T");
$si = array("1" => "m", "2" => "g" , "3" => "s");
$division = array("1" => "1", "2" => "10", "3" => "100", "4" => "1000", "5" => "10000");


function mb_str_split( $string ) {
    # Split at all position not after the start: ^
    # and not before the end: $
    return preg_split('/(?<!^)(?!$)/u', $string );
}

if (isset($_GET['action']))
{
  $starttime = microtime(true);
echo "<center>";
echo "<h3>Enhetsomvandling</h3>\n";
echo "<a href=\"index.php\">Klicka här för att göra ett nytt test</a>";
  echo "<h3>Resultat</h3>";
  echo "<pre>
  Svaren presenteras i grundpotensform där 8E+3 = 8*10³. Medans 8E-3 är 8*10⁻³.
  </pre>";
  echo "<table border=\"0\" cellpadding=\"10\" cellspacing=\"0\">";

      echo "<tr><td style=\"border:1px solid #333;border-right:none;\">Fråga</td>
      <td style=\"border:1px solid #333;border-right:none;\">Start Värde</td>
      <td style=\"border:1px solid #333;border-right:none;\">Start enhet</td>
      <td style=\"border:1px solid #333;border-right:none;\">Till enhet</td>
      <td style=\"border:1px solid #333;border-right:none;\">Ditt svar</td>
      <td style=\"border:1px solid #333\">\"Rätt\" svar</td></tr>";

  $prefix_sci = array("p" => "-12", "n" => "-9", "\xC2\xB5" => "-6", "m" => "-3", "c" => "-2",
  "d" => "-1", "si" => "0", "da" => "1", "h" => "2", "k" => "3",  "M" => "6", "G" => "9", "T" => "12");

  for ($round = 0; $round < 20  ; $round++)
  {
    $inValue = "value".($round+1);
    $startVal = $_POST[$inValue]." ";



    $value = $startVal;

    $inUnit = "startunit".($round+1);
    $realStartPrefix = $_POST[$inUnit];

    $toUnit = "tounit".($round+1);
    $realToPrefix = $_POST[$toUnit];

    $question = "question".($round+1);
    $userInput = $_POST[$question];

  $tmpValue; // used to store the temporary result

  $operation = "";
  $isFirstZero = "true";
  $exponent = "";

  $splitStartPrefix = mb_str_split($realStartPrefix);
  $splitToPrefix = mb_str_split($realToPrefix);

  $startprefix = $splitStartPrefix[0];
  $toprefix = $splitToPrefix[0];

  if (mb_strlen($realStartPrefix) == 1 || mb_strlen($realStartPrefix) == 2 && $splitStartPrefix[1] != "²" && $splitStartPrefix[1] != "³")
  {
    if (mb_strlen($realStartPrefix) == 1)
    {
        $startprefix = "si";
    }

    if (mb_strlen($realToPrefix) == 1)
    {
        $toprefix = "si";
    }
  //  echo "s: " . $prefix_sci[$startprefix] ." t: ".$prefix_sci[$toprefix]. " | ";
    if((int)$prefix_sci[$startprefix] < (int)$prefix_sci[$toprefix])
    {

      //  echo " [s < t] ". ($round+1) ."<br>";
      $operation = "-";
      $sumprefix = abs(($prefix_sci[$startprefix] - $prefix_sci[$toprefix]));
    }
    if((int)$prefix_sci[$startprefix] > (int)$prefix_sci[$toprefix])
    {
    //  echo " [s > t] ". ($round+1) ."<br>";
      $operation = "+";
      $sumprefix = abs(($prefix_sci[$toprefix] - $prefix_sci[$startprefix]));
    }
  //  echo $sumprefix. "..  ";

    //convert the start value to sci value
    $startValueArr = mb_str_split($value);

    //echo $value. " - ";
    if($value > 9)
    {
    //  echo "bigger than 9 } ";
      while((int)$value > 9)
      {
        $value = $value / 10;
      //  echo $value. " - ";
        $exponent++;
      }
    }
    else
    {
    //  echo "less than 1 } ";
      while((int)$value < 1)
      {
        $value = $value * 10;
        //echo $value. " - ";
        $exponent--;
      }
    }

    if($operation == "+")
    {
      $exponent = $exponent + abs($sumprefix);
    }
    else
    {
      $exponent = $exponent - $sumprefix;
      $exponent = $operation . abs($exponent);
    }

    //$exponent = $operation.abs($exponent);
  //  echo "(".$exponent.")";
    //$value = ($startVal*(10**$difference));

  }
  else
  {
    //calculate for kubik and kvadrat

    $squareOrCube = $splitStartPrefix[2];
    if(mb_strlen($realToPrefix) == 2)
    {
      $toprefix = "si";
      $squareOrCube = $splitStartPrefix[1];
    }
    //echo "<br><br>".mb_strlen($realToPrefix)."<br><br>";
    if((int)$prefix_sci[$startprefix] < (int)$prefix_sci[$toprefix])
    {
      $operation = "-";
      $sumprefix = abs(($prefix_sci[$startprefix] - $prefix_sci[$toprefix]));
    }
    if((int)$prefix_sci[$startprefix] > (int)$prefix_sci[$toprefix])
    {
      $operation = "+";
      $sumprefix = abs(($prefix_sci[$toprefix] - $prefix_sci[$startprefix]));
    }
    $sumprefix = abs(($prefix_sci[$startprefix] - $prefix_sci[$toprefix]));
    //echo $sumprefix . "<br>";
    if($squareOrCube == "²")
    {
      $increaseBy = "2";
    }
    if($squareOrCube == "³")
    {
      $increaseBy = "3";
    }
  //  echo $value . " - ";
  if($value > 9)
  {
  //  echo "bigger than 9 } ";
    while((int)$value > 9)
    {
      $value = $value / 10;
    //  echo $value. " - ";
      $exponent++;
    }
  }
  else
  {
  //  echo "less than 1 } ";
    while((int)$value < 1)
    {
      $value = $value * 10;
      //echo $value. " - ";
      $exponent--;
    }
  }

  if($operation == "+")
  {
    $exponent = $exponent + ($sumprefix * $increaseBy);
    //$exponent = $exponent.abs($sumprefix);
  }
  else
  {

    $exponent = $exponent - ($sumprefix * $increaseBy);
    //$exponent = $operation . abs($exponent);
  }
}
/*
  echo $value . " - ";
  $value = sprintf("%f", $value);
  $value = rtrim($value, '0');
  $value = rtrim($value, '.');
  echo $value."<br>";
*/
  //echo "<br>";
  echo "<tr><td style=\"border:1px solid #333;border-right:none;border-top:none;\">".($round+1)."</td>
  <td style=\"border:1px solid #333;border-right:none;border-top:none;\">".$startVal."</td>
  <td style=\"border:1px solid #333;border-right:none;border-top:none;\">".$realStartPrefix."</td>
  <td style=\"border:1px solid #333;border-right:none;border-top:none;\">".$realToPrefix."</td>
  <td style=\"border:1px solid #333;border-right:none;border-top:none;\">".$userInput."</td>
  <td style=\"border:1px solid #333;border-top:none;\">".$value."E".$exponent."</td></tr>";

}
  $endtime = microtime(true);
  echo "</table>";
  $timetaken = $endtime - $starttime;
  echo "Det tog: " . $timetaken . "ms att rätta svaren";
  echo "</center>";
}
else {
?>
<h3>Enhetsomvandling</h3>
<pre style="float:left; margin-right:10%;font-size:11pt">
Skriv ditt svar i boxen för varje fråga.
Det finns 20 frågor och rätt svar presenteras efter du tryckt på "rätta".
Om du känner att resultatet på en fråga blev fel så får du gärna skicka
en skärmdump på den frågan (märk ut vilken fråga det gäller). Skicka
sedan skärmdumpen till mig på facebook <a href="https://www.facebook.com/skiyte">Eric Johansson</a>. Eller visa
under någon föreläsning.
Tryck <a href="prefix.php">här</a> ifall du vill vässa dina kunskaper om prefix.

piko  - p  10⁻¹²
nano  - n  10⁻⁹
mikro - &#181;  10⁻⁶
milli - m  10⁻³
centi - c  10⁻²
deci  - d  10⁻¹
--------------
deka  - da 10¹
hekto - h  10²
kilo  - k  10³
mega  - M  10⁶
giga  - G  10⁹
tera  - T  10¹²


</pre>
<div style="margin:auto">
<?php

echo "<form action=\"?action=correct\" method=\"post\">";
echo "<table border=\"0\">";

    echo "<tr><td> </td><td> </td><td> </td><td> </td></tr>";

  for ($i = 0; $i < 20; $i++)
  {
    $rand_type = rand(1,3);
    $rand_si = rand(1,3);

        $s = 1; //start val for prefix
        $m = 11; //end val for prefix

    if ($rand_si == 3 || $rand_si == 2) // if si-unit is second
    {
      $rand_type = 1;
      $s = 1;
      $m = 4;
    }
    if ($rand_type == 2 || $rand_type == 3)
    {
        $s = 4;
        $m = 8;
    }
    if ($rand_si == 1)
    {
      $s = 1;
      $m = 8;
    }

    $rand_prefix1 = rand($s, $m);
    $rand_prefix2 = rand($s, $m);

    while ($rand_prefix1 == $rand_prefix2)
    {
      $rand_prefix1 = rand($s, $m);
    }

    // TODO
    // FIXA RÄTTNING

    $value1 = rand(1, 999)/$division[rand(1,5)];
    $unit1 = $prefix[$rand_prefix1] . $si[$rand_si] . $types[$rand_type];
    $unit2 = $prefix[$rand_prefix2] . $si[$rand_si] . $types[$rand_type];

    echo "<input type=\"hidden\" name=\"value".($i+1)."\" value=\"".$value1."\">\n";
    echo "<input type=\"hidden\" name=\"startunit".($i+1)."\" value=\"".$unit1."\">\n";
    echo "<tr><td>" . ($i+1) . ".\t </td><td>". $value1 . $unit1 ."</td><td> =</td>
    <td><input type=\"text\" size=\"10\" autocomplete=\"off\" name=\"question".($i+1)."\">".$unit2."</td></tr>\n";
    echo "<input type=\"hidden\" name=\"tounit".($i+1)."\" value=\"".$unit2."\">\n\n";

  }
  echo "<tr><td></td><td></td><td></td><td><input type=\"submit\" value=\"Rätta\"></td></tr>";
  echo "</table>";
  echo "</form>";
echo "</div>";
}


?>
</body>
</html>
