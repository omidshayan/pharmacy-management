<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    $getSTATS = ('http://ipinfo.io/json');
    $parseSTATS = file_get_contents($getSTATS);
    $statsDECODE = json_decode($parseSTATS, TRUE);
    $uIP = $statsDECODE['ip'];
    $coun = $statsDECODE['country'];
    var_dump($statsDECODE);
    ?>
</body>

</html>