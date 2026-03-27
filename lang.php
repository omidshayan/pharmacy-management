<?php

if(isset($_COOKIE['lang'])){
    if($_COOKIE['lang'] == 'fa'){
    $_COOKIE['lang'] = 'fa';
}
else if($_COOKIE['lang'] == "pashto"){
    $_COOKIE['lang'] = 'pashto';
}
else if($_COOKIE['lang'] == "en"){
    $_COOKIE['lang'] = 'en';
}
else{
    $_COOKIE['lang'] = 'fa';
}
}
else{
    $_COOKIE['lang'] = 'fa';
}



// if(isset($_SESSION['lang'])){
//     if($_SESSION['lang'] == 'fa'){
//     $_SESSION['lang'] = 'fa';
// }
// else if($_SESSION['lang'] == "pashto"){
//     $_SESSION['lang'] = 'pashto';
// }
// else if($_SESSION['lang'] == "en"){
//     $_SESSION['lang'] = 'en';
// }
// else{
//     $_SESSION['lang'] = 'fa';
// }
// }
// else{
//     $_SESSION['lang'] = 'fa';
// }


