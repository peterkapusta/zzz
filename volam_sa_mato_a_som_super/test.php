<?php
include_once './ImageResizer.php';
  
 //indicate which file to resize (can be any type jpg/png/gif/etc...)
$file = '../images/bg_2.jpg';

//indicate the path and name for the new resized file
$resizedFile = '../images/resized2.jpg';

//call the function (when passing path to pic)

$mageResizer = new ImageResizer();
$mageResizer->smart_resize_image($file , null, 350 , 200 , false , $resizedFile , false , false ,100 );

//done!
  
  ?>