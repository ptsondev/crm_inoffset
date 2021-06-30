<?php 
    require_once '../mylib.php';
    require_once '../include.php';
    
?>

<body class="page-print">
   <div class="container">
        <h1>Tính Giá Tờ Rơi</h1>
                                
       
        <div class="row">
        <div class="col-sm-3 col-xs-12">
        <?php                        
            $kt='32x43';
            $soBan = 0;
            
            if(isset($_REQUEST['kt'])){
                $kt = $_REQUEST['kt'];
            }                    
            if(isset($_REQUEST['soBan'])){
                $soBan = $_REQUEST['soBan'];
            }
        ?>
        <form id="frmBrochure">
            <label>Kích thước: </label>
            <select name="kt">
                <option value="32x43" <?php if($kt=='32x43') echo "selected"; ?> >32x43</option>
                <option value="43x65" <?php if($kt=='43x65') echo "selected"; ?> >43x65</option>
                <option value="65x86" <?php if($kt=='65x86') echo "selected"; ?> >65x86</option>                
            </select>  <br />
            
            <label>Số bản in: </label>
            <input type="numeric" name="soBan" value="<?php if($soBan!=0) echo $soBan; ?>" />  <br />
            
            <input type="submit" name="btnSubmit" /><br />
            
        </form>
        </div>
            
        <div class="col-sm-9 col-xs-12">
            <h3>Kết Quả</h3>
        <?php                 
            if(isset($_REQUEST['btnSubmit'])){
               echo  TinhGiaIn($kt, $soBan);
                 
            }
                    
        ?>
        </div>
    </div>
</div>
</body>

<?php 
function TinhGiaIn($kt, $soBan){
    if($kt=='32x43'){
        if($soBan <= 3000){
            return 600000;       
        }else{
            return 700000;
        }
    }
    if($kt=='43x65'){
        if($soBan <= 3000){
            return 1250000;       
        }
        if($soBan > 3000 && $soBan > 5000){
            return 1350000;
        }
        if($soBan>=5000){
            return (60*$soBan*1)+380000; // 1 màu, 380k=ghi kẽm
        }
    }
    if($kt=='65x86'){
        if($soBan <= 3000){
            return 1250000;       
        }
        if($soBan > 3000 && $soBan > 5000){
            return 1350000;
        }
        if($soBan>=5000){
            return 'xxx';
            return (60*$soBan*1)+380000; // 1 màu, 380k=ghi kẽm
        }
    }                 
}
?>