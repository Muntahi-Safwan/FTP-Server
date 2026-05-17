<?php
    $src = $_GET['src'];
    include('../model/t_content_model.php');
    $ext = explode('/', $src);
    $count = count($ext)-1;
    
    $dest = '../public/downloads/' . time() . $ext[$count]; 
    $source = '../public/uploads/contents/' . $ext[$count];

    if(copy($source, $dest)){
        echo "Success";
        downloadInc($src);
    }else{
        echo "error while downloading";
    }

?>