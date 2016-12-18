<?php
/**
 * Created by PhpStorm.
 * User: tricoman
 * Date: 11/12/16
 * Time: 14:18
 */
/*$targetPath = basename($_FILES['uploadedfile']['name']);

if (move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $targetPath)) {
    echo "Archivo". $targetPath . "subido correctamente";
} else {
    echo "Error al subir el archivo ";
    var_dump($_FILES);
}*/

/*$uploads_dir = '/home/tricoman/Desktop/veeruUploads';
if(is_uploaded_file($_FILES['userfile']['tmp_name'])) {
    echo  "File ".  $_FILES['userfile']['name']  ." uploaded successfully to 
$uploads_dir/$dest.\n";
    $dest=  $_FILES['userfile'] ['name'];
    move_uploaded_file ($_FILES['userfile'] ['tmp_name'], "$uploads_dir/$dest");
} else {
    echo "Possible file upload attack: ";
    echo "filename '". $_FILES['userfile']['tmp_name'] . "'.";
    print_r($_FILES);
}*/

$target_path = "uploads/";

$target_path = $target_path . basename( $_FILES['uploadedfile']['name']);

if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
    echo $target_path;
} else{
    echo "Error saving the image!";
}


