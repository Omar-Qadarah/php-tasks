<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>php basic task</title>
    <style>
        td{
            padding : 3px;
            border: 0.5px solid #000;
        }
        table{
            border-collapse: separate;
            border-spacing: 0px;

        }
    </style>
</head>
<body>
    <?php
        $colors=["white ", "green ","red "];
        echo (
            "<ul>
                <li>$colors[1]</li>
                <li>$colors[2]</li>
                <li>$colors[0]</li>
             </ul><br><br>"
            );

            echo "========================================================================== <br>";
            $cities= ["Italy"=>"Rome", "Luxembourg"=>"Luxembourg", "Belgium"=> "Brussels","Denmark"=>"Copenhagen", "Finland"=>"Helsinki", "France" => "Paris", "Slovakia"=>"Bratislava","Slovenia"=>"Ljubljana", "Germany" => "Berlin", "Greece" => "Athens", "Ireland"=>"Dublin","Netherlands"=>"Amsterdam", "Portugal"=>"Lisbon", "Spain"=>"Madrid" ];
            echo "The capital of Netherlands is " . $cities["Netherlands"] . "<br> <br>";
            echo "The capital of Greece is " . $cities["Greece"] . "<br> <br>";
            echo "The capital of Germany is " . $cities["Germany"] . "<br> <br>";

            echo "========================================================================== <br><br>";
            $color = array (4 => 'white', 6 => 'green', 11=> 'red');
            echo reset($color) . "<br><br>";
            echo "========================================================================== <br><br>";

            $num=[1,2,3,4,5];
            array_splice($num,3,0, "$");
            foreach($num as &$i){
                static $i=0;
                echo $num[$i];
                $i++;
            }
            echo "<br><br>";
            
            echo "========================================================================== <br><br>";
            $fruits = array("d" => "lemon", "a" => "orange", "b" => "banana", "c" => "apple");
            asort ($fruits);
            foreach($fruits as $x => $y ){
            echo $x . " = " . $y . "<br><br>";
            }


            echo "========================================================================== <br><br>";

            $temp=[78, 60, 62, 68, 71, 68, 73, 85, 66, 64, 76, 63, 75, 76, 73, 68, 62, 73, 72, 65, 74, 62, 62, 65, 64, 68, 73, 75, 79, 73];
            $sum = 0;
            foreach($temp as $x){
                $sum+=$x;
            }
            echo "Average Temperature is: " . $sum/count($temp)  . "<br><br>";
            rsort($temp);
            echo "List of five highest temperatures: " . implode(", " , array_slice($temp, 0, 5)) . "<br><br>";
            sort($temp);
            echo "List of five lowest temperatures: " . implode(", " , array_slice($temp, 0, 5)) . "<br><br>";

            echo "========================================================================== <br><br>";
            $array1 = array("color" => "red", 2, 4);
            $array2 = array("a", "b", "color" => "green", "shape" => "trapezoid", 4);
            $array3 = array_merge($array1, $array2);
            foreach($array3 as $x => $y ){
            echo $x . " => " . $y . "<br><br>";
            }
            echo "========================================================================== <br><br>";
            $colors = array("red","blue", "white","yellow");
            $color_c = array_map('strtoupper', $colors);
            foreach($color_c as $i){
                echo $i . "<br><br>";
            }
            // print_r($color_c);
            echo "========================================================================== <br><br>";

            $p = 6;
            if ($p % 2 !== 0) {
                echo $p . " is a prime number." . "<br><br>";
            }else echo $p . " is an even number." . "<br><br>";
            echo "========================================================================== <br><br>";
            $string = "remove";
            echo strrev($string) . "<br><br>";
            echo "========================================================================== <br><br>";
            $x = 12;
            $y= 10;
            echo "x = " . $x . "     ||   y = " . $y . "<br><br>";

            [$x, $y] = [$y , $x];

            echo "x = " . $x . "     ||   y = " . $y . "<br><br>";
            echo "========================================================================== <br><br>";
            $arm = 407;
            $ch = 0;
            $digits = str_split($arm);
            foreach($digits as $digit ){
                $ch += $digit**3;

            }
            if ($arm == $ch) {
                echo $arm . " is an armsronge number. <br><br>";
            } else echo $arm . " is not an armsronge number. <br><br>";
            echo "========================================================================== <br><br>";
            $pal = "eva can I see bees in a cave";
            echo $pal . "<br><br>";
            $clean_pal = strtolower(str_replace(' ', '', $pal));
            if($clean_pal == strrev($clean_pal)){
                echo "Yes it is a palindrome <br><br>";
            }else echo "No it is not a palindrome <br><br>";
            echo "========================================================================== <br><br>";
            $array1 = array(2, 4, 7, 4, 8, 4);
            $array2 = array_unique($array1);
            print_r ( $array2); 

            echo "<br><br> ========================================================================== <br><br>";
            $f = 10;
            $s = 20;
            if ($f + $s === 30) {
                echo $f + $s;
            }else echo "false";
            echo "<br><br> ========================================================================== <br><br>";
            $p = 9;
            if($p % 3 == 0){
                echo $p . " is a multiple of 3.";
            } else echo $p . " is not a multiple of 3.";
            echo "<br><br> ========================================================================== <br><br>";
            $p = 50;
            if ($p >= 20 && $p <=50) {
                echo $p . " is in the range of [20-50]";
            }else echo $p . " is not in the range of [20-50]";
            echo "<br><br> ========================================================================== <br><br>";
            $array1 = [1, 5, 9];
            rsort($array1);
            echo $array1[0];
            echo "<br><br> ========================================================================== <br><br>";
            $consumption = 180;
            $cost=0;
            if ($consumption > 250){
                $cost += (($consumption-250)*7.5 + 100*6.2 + 100 * 5 + 50 * 2.5);
                echo "The monthly electricity bill cost is " . $cost . " JOD.";
            }else if($consumption >= 150){
                $cost += (($consumption-150) * 6.2 + 100 * 5 + 50 * 2.5);
                echo "The monthly electricity bill cost is " . $cost . " JOD.";
            }else if ($consumption >= 50) {
                $cost += (($consumption- 50) * 5 + 50 * 2.5);
                echo "The monthly electricity bill cost is " . $cost . " JOD.";
            }else echo "The monthly electricity bill cost is " . $consumption * 2.5 . " JOD.";
            echo "<br><br> ========================================================================== <br><br>";
            $num1 = 20;
            $num2 = 5;
            $operator = "+";
            switch ($operator) {
                case "+": $result = $num1 + $num2;
                break;
                case "-":
                 $result = $num1 - $num2;
                break;
                case "*":
                 $result = $num1 * $num2;
                break;
                case "/":
                 if ($num2 != 0) {
                 $result = $num1 / $num2; 
                } else { $result = "Cannot divide by zero"; 
                }   break;
                default: $result = "Invalid operator";
            } echo "$num1 $operator $num2 = $result";
            echo "<br><br> ========================================================================== <br><br>";

            $age = 20;
            if ($age >= 18) {
                echo "You are " . $age . " so you can not vote";
            } else echo "You are " . $age . " so you can not vote";

            echo "<br><br> ========================================================================== <br><br>";
            $num = -3;
            if ($num > 0) {
                echo $num . " is a positive number.";   
            } else if ($num < 0){
                echo $num . " is a negative number.";
            } else echo $num . " ... Zero.";
            echo "<br><br> ========================================================================== <br><br>";
            $score = [60,86,95,63,55,74,79,62,50];
            $avg =  array_sum($score)/count($score);
            if ($avg < 60) {
                echo "Your avg is F";
            }else if ($avg < 70){
                echo "Your avg is D";
            }else if ($avg < 80){
                echo "Your avg is C";
            }else if ($avg < 90){
                echo "Your avg is B";
            }else if ($avg < 100){
                ECHO "Your avg is A";
            }
            echo "<br><br> ========================================================================== <br><br>";
            for ($i=1; $i <=10 ; $i++) { 
                echo $i;
                if ($i<10) {
                    echo " - ";
                }
            }
            echo "<br><br> ========================================================================== <br><br>";
            $sum=0;
            for ($i=0; $i <= 30; $i++) { 
                $sum += $i;
            }
            echo "The total number is " . $sum;
            echo "<br><br> ========================================================================== <br><br>";

            for ($i=0; $i < 5; $i++) { 
                for ($j=0; $j < 5; $j++) { 
                    if ($i == 0){
                        echo "A ";
                    }else if ($i == 1){
                        if($j<3){
                            echo "A ";
                        }else echo "B ";
                    }else if ($i==2){
                        if($j<2){
                            echo "A ";
                        }else echo "C ";
                    }else if ($i == 3){
                            if($j<1){
                            echo "A ";
                        }else echo "D ";
                    }else echo "E ";
                }
                echo "<br>";
            }
            echo "<br><br> ========================================================================== <br><br>";
            for ($i=0; $i < 5; $i++) { 
                for ($j=0; $j < 5; $j++) { 
                    if ($i == 0){
                        echo "1 ";
                    }else if ($i == 1){
                        if($j<3){
                            echo "1 ";
                        }else echo "2 ";
                    }else if ($i==2){
                        if($j<2){
                            echo "1 ";
                        }else echo "3 ";
                    }else if ($i == 3){
                            if($j<1){
                            echo "1 ";
                        }else echo "4 ";
                    }else echo "5 ";
                }
                echo "<br>";
            }
            echo "<br><br> ========================================================================== <br><br>";
            for ($i=1; $i < 6; $i++) { 
                for ($j=1; $j < 6; $j++) { 
                    if($i==$j){
                        echo $i . " ";
                    }else echo 0 . " ";
                }
                echo "<br>";
            }
            echo "<br><br> ========================================================================== <br><br>";
            $num = 6;
            $fac = 1;
            for ($i= $num ; $i > 0 ; $i--) { 
                $fac *= $i;
            }
            echo "The factorial of " . $num . " is ". $fac;
            echo "<br><br> ========================================================================== <br><br>";
            echo "<table>";
            for ($i=1; $i <= 6; $i++) { 
                echo "<tr>";
                for ($j=1; $j < 6; $j++) { 
                    echo "<td>".  $i . " * " . $j . " = " . $i * $j . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
            echo "<br><br> ========================================================================== <br><br>";
            $str = "Omar";

            echo strtoupper($str) . "<br>" . strtolower($str) . "<br>" . ucfirst($str) . "<br>" . lcfirst($str) . "<br>" . ucwords($str);
            echo "<br><br> ========================================================================== <br><br>";
            $time = "085119";
            $chunks = str_split($time, 2);
            $output = implode(':', $chunks);
            echo $output;
            echo "<br><br> ========================================================================== <br><br>";
            $line = "I am a full stack developer at orange coding academy";
            $word = "oranges";
            if (str_contains($line, $word)) {
                echo "Word found.";
            }else echo "Word not found.";
            echo "<br><br> ========================================================================== <br><br>";
            $filename = basename($_SERVER['PHP_SELF']);
            echo $filename;

            echo "<br><br> ========================================================================== <br><br>";
            $email = "info@omar.com";
            echo strstr($email, "@", true);
            
            echo "<br><br> ========================================================================== <br><br>";
            echo substr($filename, -3);
            echo "<br><br> ========================================================================== <br><br>";

            echo "<br><br> ========================================================================== <br><br>";
            $sentence = "That new trainee is so genius.";
            $word = "Our";
            $arr = explode(" " ,$sentence);
            $arr[0] = $word;
            $sentence = implode(" ", $arr);
            echo $sentence;
            echo "<br><br> ========================================================================== <br><br>";
        ?>

</body>
</html>
