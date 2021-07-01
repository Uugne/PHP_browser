<?php
session_start();

// Logout logic
if (isset($_GET['action']) and $_GET['action'] == 'logout') {
    unset($_SESSION['username']);
    unset($_SESSION['password']);
    unset($_SESSION['logged_in']);
    print('Logged out!');
}

// Login logic
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
};

//Download logic
if ($_SESSION['logged_in'] == true) {
    if(isset($_POST['download'])){
        $file= './' . $_GET['path'];
        $fileToDownloadEscaped = str_replace("&nbsp;", " ", htmlentities($file, null, 'utf-8'));
        ob_clean();
        ob_start();
            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf'); 
            header('Content-Disposition: attachment; filename=' . basename($fileToDownloadEscaped));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fileToDownloadEscaped)); 
        ob_end_flush();
        readfile($fileToDownloadEscaped);
        exit;
    }
};

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

print '<hr><hr>';

//Back button 

print '<br><br>';

$goBack = substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'],basename($_SERVER['SCRIPT_NAME'])));

if ($_SESSION['logged_in'] == true) {
    print '<button>' . 
                '<a class="button" href=' . $goBack . '>'."BACK TO MAIN".'</a>' .
          '</button>';
};

?>
<!-- Logout button -->
<div>
    <form action="./index.php" method="post" <?php $_SESSION['logged_in'] == true ? print("style = \"display: none\"") : print("style = \"display: block\"") ?>>
        <h4><?php echo $msg; ?></h4>
        <input type="text" name="username" placeholder="username = user" required autofocus></br>
        <input type="password" name="password" placeholder="password = pass" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit" name="login">Login</button>
    </form>
<?php if ($_SESSION['logged_in'] == true) print '<button><a class="log" href="index.php?action=logout"> > logout</button>'?>
</div>

<?php

// Make a directory logic

if ($_SESSION['logged_in'] == true) {
    if(isset($_POST["Submit1"])){
    mkdir($_POST["str"]);
    echo "Directory Created.";
    };

print '<form method="POST">
            Enter directory name: <input type="text" name="str">
            <input type="submit" name="Submit1" value="Create Directory">
      </form>';

print '<br>';
};

// Delete a directory logic
$items = scandir(__FILE__);

$status=unlink($items);    
if($status){  
echo "File deleted successfully";    
}else{  
echo "Sorry!";    
} 
?>

<br>
<br>

<!-- Upload file form -->

<?php if ($_SESSION['logged_in'] == true) print '<p>Upload a picture:<p>
    <form action = "" method = "POST" enctype = "multipart/form-data">
        <input type = "file" name = "image">
        <input type = "submit">
    </form>' 
?>   

<?php 

// Upload logic

if ($_SESSION['logged_in'] == true) {
    if(isset($_FILES['image'])){
        $errors= array();
        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_type = $_FILES['image']['type'];
        $file_ext = strtolower(end(explode('.',$_FILES['image']['name'])));
        $extensions = array("jpeg","jpg","png");
            if(in_array($file_ext,$extensions)=== false){
                $errors[]="extension not allowed, please choose a JPEG or PNG file.";
            }
            if($file_size > 2097152) {
                $errors[]='File size must be smaller than 2 MB';
            }
            if(empty($errors)==true) {
                move_uploaded_file($file_tmp,"./".$file_name);
                print 'Success!';
            }else{
                print_r($errors);
        }
    }
}   

?>
<br>
<br>

<!-- Download form -->
<?php
    if ($_SESSION['logged_in'] == true) {
        print '<br>Download a file:';
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
</body>

</html>


