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
    print '<div class="title"><br>' . '<p id="type">Type</p>' . ' ' . '<p id="name">Name</p><br></div>';
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

if ($_SESSION['logged_in'] == true) 
    print'<hr>';


//Back button 

print '<br>';

$goBack = substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'],basename($_SERVER['SCRIPT_NAME'])));

if ($_SESSION['logged_in'] == true) {
    print '<button id="button">' . 
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
        <button type="submit" name="login">Login</button>
    </form>
<?php if ($_SESSION['logged_in'] == true) print '<button><a class="log" href="index.php?action=logout"> > logout</button></a>'?>
</div>

<?php if ($_SESSION['logged_in'] == true) 
    print'<br><br><hr class="light"><br>';
?>

<!-- Upload file form -->

<?php if ($_SESSION['logged_in'] == true) print '&nbsp&nbsp&nbspUpload a picture:
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
            if(in_array($file_ext,$extensions) === false){
                $errors[]="extension not allowed, please choose a JPEG or PNG file.";
            }
            if($file_size > 2097152) {
                $errors[]='File size must be smaller than 2 MB';
            }
            if(empty($errors)==true) {
                move_uploaded_file($file_tmp,"./".$path.'/'.$file_name);
                header("Refresh:0");
            } else {
                print_r($errors);
        }
    }
}   

?>
<?php if ($_SESSION['logged_in'] == true) 
    print'<br><br><hr class="light">';
?>

<!-- Download form -->

<?php

if ($_SESSION['logged_in'] == true) {
    if ($handle = opendir('./'.$path)) {
        print '<br>&nbsp&nbsp&nbspDownload a file:';
        while (false !== ($entry = readdir($handle))) {
            if ($entry != "." && $entry != ".." && $entry != '.git' && !is_dir($entry) && strpos($entry, ".") !== false) {
                print('<form action="?path=' . $entry . '" method="POST">');
                print('<input type="submit" name="download" value="' . $entry . '"/>');
                print('</form>');
            }
        }
        closedir($handle);
    }
};
?>
<?php if ($_SESSION['logged_in'] == true) 
    print'<br><br><hr class="light"><br>';
?>
<!-- Delete a file logic -->
<?php

if ($_SESSION['logged_in'] == true) {
    if(isset($_GET['delete'])) {
    @unlink('./'.$path.$_POST['fileToDelete']);
    }
};    

if ($_SESSION['logged_in'] == true) {
    print '&nbsp&nbsp&nbspDelete a file:';
    if ($handle = opendir('./'.$path)) {  
        while (false !== ($file = readdir($handle))) {   
            if ($file != "." && $file != ".." && $file!= ".git" && strpos($file, ".") == !false && !is_dir($file)) {     
                print "<form class='delete' method=\"post\" name=\"deleteSomething\" action=";
                print $_SERVER['PHP_SELF'].'?delete=true';
                print
                    '<form method="post" action="delete.php">
                        <button class="delete" type="submit" name="fileToDelete" value='.$file.'>'.$file.'</button>
                    </form>';
                }   
        }  
        closedir($handle);   
    } 
};
?>
<?php if ($_SESSION['logged_in'] == true) 
    print'<br><br><hr class="light"><br>';
?>

<!-- Make a directory logic -->
<?php

if ($_SESSION['logged_in'] == true) {
    print '&nbsp&nbsp&nbspMake a directory:';
    if(isset($_POST['submit'])){
        $foldername = $_POST['foldername'];
        $structure = './'.$path.'/'.$foldername;
        header('refresh:0');

            if (@!mkdir($structure, 0777, true)) {
            die('Folder is created!');
            }
    }

    print '<form method="post" action="#">
            <input type="text" name="foldername">
            <input type="submit" name="submit" value="Create Folder">
           </form>';
    };
?>
</body>

</html>


