<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php

$myDirectory = '../Animals';
$myFiles = scandir('../Animals');

print '<div class="title"><br>' . '<p id="type">Type</p>' . ' ' . '<p id="name">Name</p>' . '<br></div>';
print '<br>' . '<hr>';
print '<div class="vline"></div>';

foreach ($myFiles as $key => $value) {
    if ('.' !== $value && '..' !== $value){
        if (is_dir($value))
           print '<div class="dirFile"><p>Directory</p></div>'. ' ' .  '<a href="'.$myDirectory. '/' . $value.'">' . $value . ' </a> ' . '<br><br><hr>'; elseif (
            is_file($value))
                print '<div class="dirFile"><p>File</p></div>' . ' ' . '<div class="failai">' . $value . '</div>' . '<br><br><hr>';           
    }
}

?>
</body>
</html>


