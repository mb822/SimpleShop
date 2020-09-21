<?php

//create and initializes array, arr
$arr = [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20];

//loop through and print each element of the array, arr
foreach($arr as $num){
    echo "$num\n";
}

//loop through each elemnt of the array, arr, and print only even numbers
//the modulus operator was used to determine if the number is even
//a modulus b returns the remainder of division of a/b
//if the modulus of a number and 2 is 0, the number must be even
//if this condition is met, the elemement is printed
foreach($arr as $num){
    if($num%2 == 0){echo "$num\n";}
}
   
?>