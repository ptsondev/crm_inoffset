
<!-- saved from url=(0015)http://in1.dev/ -->
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>SNH - Tính giá in brochure / catalogue / dạng cuốn</title>
        <!--CSS-->
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/main.css">
        
        <!--Jquery-->
        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/myjs.js"></script>
        
        <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
    </head>
    
    <body>
    
        <?php                        
            $size= "a4";            
            $quantity = 0;
            $pages = 0;
            $bGiay = 'C250';
            $bCan = true;
            $rGiay = 'C150';
            $rCan = false;
        
            if(isset($_REQUEST['slSize'])){
                $size = $_REQUEST['slSize'];
            }                                
            if(isset($_REQUEST['txtQuantity'])){
                $quantity = $_REQUEST['txtQuantity'];
            }
            if(isset($_REQUEST['txtPages'])){
                $pages = $_REQUEST['txtPages'];
            }
            if(isset($_REQUEST['txtQuantity'])){
                $quantity = $_REQUEST['txtQuantity'];
            }
            if(isset($_REQUEST['bGiay'])){
                $bGiay = $_REQUEST['bGiay'];
            }
            if(isset($_REQUEST['bCan'])){
                $bCan = $_REQUEST['bCan'];
            }
            if(isset($_REQUEST['rGiay'])){
                $rGiay = $_REQUEST['rGiay'];
            }
            if(isset($_REQUEST['rCan'])){
                $rCan = $_REQUEST['rCan'];
            }
        
           
        ?>
        <form>
            Kích thước: 
            <select name="slSize">
                <option value="a4" <?php if($size=='a4') echo "selected"; ?> >A4</option>                
            </select>
            
            Số trang:
            <input type="numeric" name="txtPages" value="<?php if($pages!=0) echo $pages; ?>" />  <br />
            
            Số thành phẩm: 
            <input type="numeric" name="txtQuantity" value="<?php if($quantity!=0) echo $quantity; ?>" />  <hr />
            
            <h4>Bìa</h4>
            Loại giấy:
            <select name="bGiay">
                <option value="C100" <?php if($bGiay=='C100') echo "selected"; ?> >C100</option>
                <option value="C150" <?php if($bGiay=='C150') echo "selected"; ?> >C150</option>
                <option value="C200" <?php if($bGiay=='C200') echo "selected"; ?> >C200</option>
                <option value="C250" <?php if($bGiay=='C250') echo "selected"; ?> >C250</option>
                <option value="C300" <?php if($bGiay=='C300') echo "selected"; ?> >C300</option>                                
            </select>  <br />            
            Cán màng?
            <input type="checkbox" name="bCan" <?php if($bCan) echo "checked"; ?> /> <hr />
            
            
             <h4>Ruột</h4>
            Loại giấy:
            <select name="rGiay">
                <option value="C100" <?php if($rGiay=='C100') echo "selected"; ?> >C100</option>
                <option value="C150" <?php if($rGiay=='C150') echo "selected"; ?> >C150</option>
                <option value="C200" <?php if($rGiay=='C200') echo "selected"; ?> >C200</option>
                <option value="C250" <?php if($rGiay=='C250') echo "selected"; ?> >C250</option>
                <option value="C300" <?php if($rGiay=='C300') echo "selected"; ?> >C300</option>                                
            </select>  <br />            
            Cán màng?
            <input type="checkbox" name="rCan" <?php if($rCan) echo "checked"; ?> /> <hr />
            
            
            <input type="submit" name="btnSubmit" /><br />
            
        </form>
        
        <?php                 
            if(isset($_REQUEST['btnSubmit'])){
                // kiem tra chia het cho 4
                if($pages%4!=0){
                    echo 'Loi! Tong so trang phai chia het cho 4';
                    return '';
                }
                // thoa
                $phiIn = $phiGiay = $phiGiaCong = $phi = 0;
                $phiShip = 100;
                
                $soTo6586Bia = $soTo6586 = 0;
                
                $soTrangConLai = $pages;
                if($bGiay != $rGiay){
                    // 1 kem 65x43 rieng cho bia
                    $phiIn += 800;
                    $soTo6586Bia = ceil( (($quantity/2)+50)/2 );
                    $soTrangConLai -=4;
                }
                while($soTrangConLai >=8){
                    $phiIn += 1200;
                    $soTo6586 += ceil(($quantity/2)+50);
                    $soTrangConLai -=8;
                }
                if($soTrangConLai==4){
                    $phiIn += 800;
                    $soTo6586 += ceil( (($quantity/2)+50)/2 );                    
                }
                $phiGiay = tinhGiaGiay($soTo6586Bia, $bGiay) + tinhGiaGiay($soTo6586, $rGiay);
                
                $phiGiaCong = 0.05 * $pages * $quantity;
                if($bCan){
                    $phiGiaCong += 0.06*2*2*$quantity;
                }
                if($rCan){
                    $phiGiaCong += 0.06*$pages*2*$quantity;
                }
                
                
                $phi = $phiIn + $phiGiay + $phiGiaCong + $phiShip;
                $loi = $phi * 0.7;                
                
                
                echo '<table id="tbResult">';
                    echo '<tr><td>Phí in</td><td>'.aio_display_money($phiIn).'</td></tr>';
                    echo '<tr><td>Phí giấy</td><td>'.aio_display_money($phiGiay).'</td></tr>';
                    echo '<tr><td>Phí gia công</td><td>'.aio_display_money($phiGiaCong).'</td></tr>';
                    echo '<tr><td>Phí ship</td><td>'.aio_display_money($phiShip).'</td></tr>';
                    echo '<tr><td>Giá gốc</td><td>'.aio_display_money($phi).'</td></tr>';
                    echo '<tr><td>Lời</td><td><input type="text" id="txtLoi" name="txtLoi" value="'.aio_display_money($loi).'" /></td></tr>';
                    echo '<tr><td>Giá báo</td><td><b>'.aio_display_money($phi + $loi).'</b></td></tr>';
                    echo '<tr><td>Đơn giá</td><td><i>'.aio_display_money(($phi + $loi)/$quantity ).'</i></td></tr>';
                echo '</table>';
                    
            }
                    
        ?>
        
    </body>        
</html>

<?php 
    function tinhGiaGiay($soTo, $loai){
        switch($loai){
            case 'C100':
                return $soTo/500* 575;
            case 'C150':
                return $soTo/500* 860;
            case 'C200':
                return $soTo/500* 1150;
            case 'C250':
                return $soTo/500* 1430;
            case 'C300':
                return $soTo/500* 1720;          
        }
        
    }

    function aio_display_money($value){
        if(!is_numeric($value)){
            return 0;
        }
        return number_format($value, 3, '.', '.');
    }


?>