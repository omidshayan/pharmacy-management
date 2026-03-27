<?php
$data = array();
if(isset($_FILES['upload']['name'])){
    $file_name = $_FILES['upload']['name'];
    $path = 'upload/'.$file_name;
    $file_ex = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    if($file_ex == 'jpt' ){
        if(move_uploaded_file($_FILES['upload']['tmp_name'], $path))
        {
            $data['file'] = $file_name;
            $data['url'] = $path;
            $data['uploaded'] = 1;
        }
        else{
            $data['uploaded'] = 0;
            $data['error']['message'] = 'error';
        }
    }
    else{
        $data['uploaded'];
        $data['error']['message'] = 'invalid';
    }
}
echo 'ok';