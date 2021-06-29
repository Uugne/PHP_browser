<?php
session_start();

// Logout
if (isset($_GET['action']) and $_GET['action'] == 'logout') {
    unset($_SESSION['username']);
    unset($_SESSION['password']);
    unset($_SESSION['logged_in']);
    print('Logged out!');
}

// Login
$msg = '';
if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password'])) {
    if ($_POST['username'] == 'user' && $_POST['password'] == 'pass') {
        $_SESSION['logged_in'] = true;
        $_SESSION['timeout'] = time();
        $_SESSION['username'] = $_POST['username'];
        $msg = 'You have entered valid use name and password';
    } else {
        $msg = 'Wrong username or password';
    }
}


?>
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

// Title 
if ($_SESSION['logged_in'] == true) {
    print '<div class="title"><br>' . '<p id="type">Type</p>' . ' ' . '<p id="name">Name</p>' . '<br></div>';
    print '<br>' . '<hr>';
    print '<div class="vline"></div>';
}

// Browsing through directories (and printing content)
if ($_SESSION['logged_in'] == true) {
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
}

if ($_SESSION['logged_in'] == true) {
$path = null;
if (isset($_GET['file'])) {
    $path = $_GET['file'];
    if (!inDirectory($_GET['file'], $root)) {
        $path = null;
    } else {
        $path = '/'.$path;
    }
}
}

if ($_SESSION['logged_in'] == true) {
foreach (glob($root.$path.'/*') as $file) {
    $file = realpath($file);
    $link = substr($file, strlen($root) + 1);
        if (is_dir($file))
                print '<div class="dirFile"><p>Directory</p></div>'. ' ' .  '<a href="?file='.urlencode($link).'">'.basename($file).'</a><br><br><hr>'; elseif 
            (is_file($file)) 
                print '<div class="dirFile"><p>File</p></div>' . ' ' . '<div class="failai">' . basename($file) . '</div>' . '<br><br><hr>';
}
}

// Button

// if ($_SESSION['logged_in'] == true){
// if ($root.$path !== $root)
//     print '<button id="back" onclick="history.go(-1);"><<<</button> ';
// }

?>

<div>
    <form action="./index.php" method="post" <?php $_SESSION['logged_in'] == true ? print("style = \"display: none\"") : print("style = \"display: block\"") ?>>
        <h4><?php echo $msg; ?></h4>
        <input type="text" name="username" placeholder="username = user" required autofocus></br>
        <input type="password" name="password" placeholder="password = pass" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit" name="login">Login</button>
    </form>
    <?php if ($_SESSION['logged_in'] == true) print '<a href="index.php?action=logout"> > logout'?>
    
</div>

<?php if ($_SESSION['logged_in'] == true) print '
<form action = "" method = "POST" enctype = "multipart/form-data">
        <input type = "file" name = "image">
        <input type = "submit">
    </form>' 
    ?>
    
    <?php
    if ($_SESSION['logged_in'] == true) {
        print '<br>download ';
        $dir_contents = scandir('./');
        foreach($dir_contents as $content){
            if(is_file($content)){
                print('<form action="?path=' . $content . '" method="POST">');
                print('<input type="submit" name="download" value="' . $content . '"/>');
                print('</form>');
            }
        }
    }
    ?>

<?php 

// Upload
if ($_SESSION['logged_in'] == true) {
    if(isset($_FILES['image'])){
        $errors= array();
        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_type = $_FILES['image']['type'];
        // check extension (and only permit jpegs, jpgs and pngs)
        $file_ext = strtolower(end(explode('.',$_FILES['image']['name'])));
        $extensions = array("jpeg","jpg","png");
        if(in_array($file_ext,$extensions)=== false){
            $errors[]="extension not allowed, please choose a JPEG or PNG file.";
        }
        if($file_size > 2097152) {
            $errors[]='File size must be smaller than 2 MB';
        }
        if(empty($errors)==true) {
            move_uploaded_file($file_tmp,"./".$path.$file_name);
            echo "Success";
        }else{
            print_r($errors);
        }
    }
}

//Download
print_r($_FILES);
        // file download logic
        if(isset($_POST['download'])){
            // print('Path to download: ' . './' . $_GET["path"] . $_POST['download']);
            $file='./' . $_POST['download'];
            // a&nbsp;b.txt --> a b.txt
            $fileToDownloadEscaped = str_replace("&nbsp;", " ", htmlentities($file, 'utf-8'));

            ob_clean();
            ob_start();
            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf'); // mime type → ši forma turėtų veikti daugumai failų, su šiuo mime type. Jei neveiktų reiktų daryti sudėtingesnę logiką
            header('Content-Disposition: attachment; filename=' . basename($fileToDownloadEscaped));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fileToDownloadEscaped)); // kiek baitų browseriui laukti, jei 0 - failas neveiks nors bus sukurtas
            ob_end_flush();

            readfile($fileToDownloadEscaped);
            exit;
        }

?>
</body>

</html>


