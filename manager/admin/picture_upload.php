<?php

    require_once '../mylib.php';

    require_once '../include.php';    
  

    $targetDir = dirname(__FILE__) ."/../../uploads/"; 




$PID = $_REQUEST['PID'];

$dbh = getDBH();


    // File upload configuration 
    
    $allowTypes = array('jpg','png','jpeg','gif'); 
     
    $statusMsg = $errorMsg = $insertValuesSQL = $errorUpload = $errorUploadType = ''; 
    $fileNames = array_filter($_FILES['files']['name']); 

    $resultHTML='';
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
                        $result = $stmt->execute(array($PID, 'uploads/'.$fileName));


                    $resultHTML.= '<div class="pic pic-x">';
                        $resultHTML.= '<img src="../../uploads/'.$fileName.'" class="picture"  />';
                        $resultHTML.= '<div class="rm_pic" picture_id="xxx"" >X</div>';
                    $resultHTML.= '</div>';

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
         
     
        echo $resultHTML;


    }else{ 
        $statusMsg = 'Please select a file to upload.'; 
    } 
