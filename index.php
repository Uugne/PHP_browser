<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Browser</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php

print '<div class="title"><br>' . '<p id="type">Type</p>' . ' ' . '<p id="name">Name</p>' . '<p id="action">Action</p>' . '<br></div>';
print '<br>' . '<hr>';
print '<div class="vline"></div>';

$root = __DIR__;

function inDirectory($file, $directory, $recursive = true) {
    $directory = realpath($directory);
    $parent = realpath($file);
    $i = 0;
    while ($parent) {
        if ($directory == $parent) 
        return true;
        if ($parent == dirname($parent) || !$recursive) 
        break;
        $parent = dirname($parent);
    }
    return false;
}

$path = null;
if (isset($_GET['file'])) {
    $path = $_GET['file'];
    if (!inDirectory($_GET['file'], $root)) {
        $path = null;
    } else {
        $path = '/'.$path;
    }
}

foreach (glob($root.$path.'/*') as $file) {
    $file = realpath($file);
    $link = substr($file, strlen($root) + 1);
        if (is_dir($file))
                echo '<div class="dirFile"><p>Directory</p></div>'. ' ' .  '<a href="?file='.urlencode($link).'">'.basename($file).'</a><br><br><hr>'; elseif 
            (is_file($file)) 
                echo '<div class="dirFile"><p>File</p></div>' . ' ' . '<div class="failai">' . basename($file) . '</div>' . '<br><br><hr>';
}

if ($root.$path !== $root)
    print '<button id="back" onclick="history.go(-1);"><<<</button> ';

?>

</body>

</html>


