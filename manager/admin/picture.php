<?php

    require_once '../mylib.php';

    require_once '../include.php';    
    show_header_include('Hình minh hoạ đơn hàng');

    $targetDir = dirname(__FILE__) ."/../../uploads/"; 
?>


<script>

	var PID = $('#pid_picture', window.parent.document).attr('pid'); 	

	createCookie('pid_picture', PID);

</script>



<form action="/manager/admin/picture.php" method="post" enctype="multipart/form-data">
    Select Image Files to Upload:
    <input type="file" name="files[]" multiple >
    <input type="submit" name="upload" value="UPLOAD" >
</form>


<?php 


$PID =  $_COOKIE["pid_picture"];
/*
if(isset($_REQUEST['PID'])){
    $PId = $_REQUEST['PID'];
}
*/

$dbh = getDBH();


if(isset($_REQUEST['upload'])){ 
    // File upload configuration 
    
    $allowTypes = array('jpg','png','jpeg','gif'); 
     
    $statusMsg = $errorMsg = $insertValuesSQL = $errorUpload = $errorUploadType = ''; 
    $fileNames = array_filter($_FILES['files']['name']); 
    if(!empty($fileNames)){ 
        foreach($_FILES['files']['name'] as $key=>$val){ 
            // File upload path 

            $fileName = time().'---'.basename($_FILES['files']['name'][$key]);

            $fileName = basename($_FILES['files']['name'][$key]); 

            $targetFilePath = $targetDir . $fileName; 
             
            // Check whether file type is valid 
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); 
            if(in_array($fileType, $allowTypes)){ 
                // Upload file to server 
                if(move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFilePath)){ 
                    // Image db insert sql 
                    $insertValuesSQL .= "('".$fileName."', NOW()),"; 
                        $sql = 'INSERT INTO pictures (PID, url) VALUES (?,?)';

                        $stmt = $dbh->prepare($sql);

                        $result = $stmt->execute(array($PID, $fileName));

                }else{ 
                    $errorUpload .= $_FILES['files']['name'][$key].' | '; 
                } 
            }else{ 
                $errorUploadType .= $_FILES['files']['name'][$key].' | '; 
            } 
        } 
         
        // Error message 
        $errorUpload = !empty($errorUpload)?'Upload Error: '.trim($errorUpload, ' | '):''; 
        $errorUploadType = !empty($errorUploadType)?'File Type Error: '.trim($errorUploadType, ' | '):''; 
        $errorMsg = !empty($errorUpload)?'<br/>'.$errorUpload.'<br/>'.$errorUploadType:'<br/>'.$errorUploadType; 
         
       //echo $insertValuesSQL;


    }else{ 
        $statusMsg = 'Please select a file to upload.'; 
    } 
} 
?>






<?php 


$arrPics = loadProjectPictures($PID);
echo '<div id="project_pictures">';
foreach ($arrPics as $pic_id => $url){
    echo '<div class="pic pic-'.$pic_id.'">';
        echo '<img src="../../uploads/'.$url.'" class="picture"  />';
        echo '<div class="rm_pic" picture_id="'.$pic_id.'"" >X</div>';
    echo '</div>';
}
echo '</div>';
?>
