<?php
include_once './ImageResizer.php';

$valid_formats = array("jpg", "png", "gif", "zip", "bmp");
$max_file_size = 1024 * 1024 * 20; //100 kb
$path = "../images/locations/"; // Upload directory
$count = 0;

if (isset($_GET['location'])) {
    $location = $_GET['location'];

    $db = new PDO('mysql:host=mysql51.websupport.sk;dbname=kamnabic;port=3309', 'tlhl3ze3', 'jq78Nh234Pm');

    $st = $db->prepare("SELECT images FROM location WHERE alias = ?");
    $st->execute(array($location));
    $row = $st->fetch(PDO::FETCH_ASSOC);

    $locatoinFileCount = (int) $row['images'];
}

if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST" && isset($_GET['location'])) {
    // Loop $_FILES to execute all files

    foreach ($_FILES['files']['name'] as $f => $name) {

        $newImage = true;
        if ($_FILES['files']['error'][$f] == 4) {
            continue; // Skip file if any error found
        }
        if ($_FILES['files']['error'][$f] == 0) {
            $fileName = $location . '_' . $locatoinFileCount . '.jpg';
            if ($_FILES['files']['size'][$f] > $max_file_size) {
                $message[] = "$name is too large!.";
                continue; // Skip large files
            } elseif (!in_array(pathinfo($name, PATHINFO_EXTENSION), $valid_formats)) {
                $message[] = "$name is not a valid format";
                continue; // Skip invalid file formats
            } else { // No error found! Move uploaded files 
                if (move_uploaded_file($_FILES["files"]["tmp_name"][$f], $path . $fileName)) {
                    
                    if($newImage) {
                        $resizedFile = $path . $location . '.jpg';
                        $file = $path . $fileName;
                        $mageResizer = new ImageResizer();
                        $mageResizer->smart_resize_image($file , null, 350 , 200 , false , $resizedFile , false , false ,100 );
                        $newImage = false;
                    }
                    $count++; // Number of successfully uploaded files
                    $locatoinFileCount++;
                    
                    $st = $db->prepare('UPDATE location SET images = ? WHERE alias = ?');
                    $st->execute(array($locatoinFileCount, $location));
                }
            }
        }
    }
}
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <title>Multiple File Upload with PHP - Demo</title>
        <link href="style.css" rel="stylesheet">
        <link href="/zzz/css/bootstrap.min.css" rel="stylesheet">
        <script src="/zzz/js/jquery.js"></script>
        <script src="/zzz/js/bootstrap.min.js"></script>

    </head>
    <body>
        <div class="wrap">

<?php
if (isset($_GET['location'])) {
    echo '<h1>' . $_GET['location'] . '</h1>';
    echo '<h1>Count of pictures: ' . $locatoinFileCount . '</h1>';
}
?>
            <?php
# error messages
            if (isset($message)) {
                foreach ($message as $msg) {
                    printf("<p class='status'>%s</p></ br>\n", $msg);
                }
            }
# success message
            if ($count != 0) {
                printf("<p class='status'>%d files added successfully!</p>\n", $count);
            }
            ?>

            <p>Max file size 20Mb, Valid formats jpg, png, gif</p>
            <br />
            <br />
            <!-- Multiple file upload html form-->
            <form action="" method="post" enctype="multipart/form-data">
                <input type="file" class="btn btn-default" name="files[]" multiple="multiple" accept="image/*">
                <input type="submit" value="Upload" class="btn btn-success">
            </form>
            <div class="row">
                <a href="http://localhost/zzz/volam_sa_mato_a_som_super/">Back to admin</a>
            </div>
        </div>
    </body>
</html>