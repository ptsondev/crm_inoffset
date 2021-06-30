
<!-- saved from url=(0015)http://in1.dev/ -->
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>SNH - Tính giá in tờ rơi</title>
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
            $soCon = 0;
            $quantity = 0;
            $paper = 'C150';
            $can = false;
            $ghep = false;
        
            if(isset($_REQUEST['slSize'])){
                $size = $_REQUEST['slSize'];
            }                    
            if(isset($_REQUEST['txtSoCon'])){
                $soCon = $_REQUEST['txtSoCon'];
            }
            if(isset($_REQUEST['txtQuantity'])){
                $quantity = $_REQUEST['txtQuantity'];
            }
            if(isset($_REQUEST['slPaper'])){
                $paper = $_REQUEST['slPaper'];
            }
            if(isset($_REQUEST['cbxCan'])){
                $can = $_REQUEST['cbxCan'];
            }
            if(isset($_REQUEST['slGhep'])){
                $ghep = $_REQUEST['slGhep'];
            }
           
        ?>
        <form>
            Kích thước: 
            <select name="slSize">
                <option value="a4" <?php if($size=='a4') echo "selected"; ?> >A4</option>
                <option value="a5" <?php if($size=='a5') echo "selected"; ?> >A5</option>
                <option value="a6" <?php if($size=='a6') echo "selected"; ?> >A6</option>
            </select> - Hoặc số con trên 1 tờ A3 <input type="text" name="txtSoCon" value="<?php if($soCon!=0) echo $soCon; ?>" /> <br />
            
            Số thành phẩm: 
            <input type="numeric" name="txtQuantity" value="<?php if($quantity!=0) echo $quantity; ?>" />  <br />
            
            Loại giấy:
            <select name="slPaper">
                <option value="C100" <?php if($paper=='C100') echo "selected"; ?> >C100</option>
                <option value="C150" <?php if($paper=='C150') echo "selected"; ?> >C150</option>
                <option value="C200" <?php if($paper=='C200') echo "selected"; ?> >C200</option>
                <option value="C250" <?php if($paper=='C250') echo "selected"; ?> >C250</option>
                <option value="C300" <?php if($paper=='C300') echo "selected"; ?> >C300</option>                                
            </select>  <br />
            
            Cán màng?
            <input type="checkbox" name="cbxCan" <?php if($can) echo "checked"; ?> /> <br />
            
            <?php if($ghep!=0) echo '<b>';?> Ghép bài?
            <select name="slGhep">
                <option value="0" <?php if($ghep==0) echo "selected"; ?> >Không ghép</option>
                <option value="2" <?php if($ghep==2) echo "selected"; ?> >Ghép 2 bài</option>
                <option value="4" <?php if($ghep==4) echo "selected"; ?> >Ghép 4 bài</option>                
            </select> <?php if($ghep!=0) echo '</b>';?> <br />
            
            <input type="submit" name="btnSubmit" /><br />
            
        </form>
        
        <?php                 
            if(isset($_REQUEST['btnSubmit'])){
                $phi = $phiIn = $phiGiay = $phiGiaCong = 0;
                
                if($soCon == 0){
                    if($size=="a4"){
                        $soCon = 2;
                    }else if($size=="a5"){
                        $soCon = 4;
                    }else if($size=="a6"){
                        $soCon = 8;
                    }
                }                                
        
                $soToA3 = ($quantity / $soCon) + 50;                
                if($soToA3 <= 4000){
                    $phiIn = 700;
                }
                if($soToA3 < 3000){
                    $phiIn = 650;
                }
                if($soToA3 > 4000){
                    $phiIn = 0.12*2*$soToA3;
                }
                /* in ghep, chi thay doi phi in */
                if($ghep==2){   // ghep 2 bai => vao 65x43
                    $phiIn = 800/2;
                }else if($ghep==4){ // ghep 4 bai => vao 65x86
                    $phiIn = 1200/4;
                }
                                
                $soTo = ceil($soToA3/4); // 65x86
                $phiGiay = ceil(tinhGiaGiay($soTo, $paper));
                
                $phiGiaCong = 40;
                if($can){
                    $phiCan = $soTo * 0.65 * 0.86 * 2; // 1 mat
                    $phiCan *= 2; // 2 mat
                    $phiGiaCong+= $phiCan;
                }
                $phiShip = 100;
                
                $phi = $phiIn + $phiGiay + $phiGiaCong;                
                
                $loi = ceil($phi * 0.6);
                // neu ghep bai thi nen an loi nhieu hon
                if($ghep==2){
                    $loi = ceil($phi * 0.7);
                }
                if($ghep==4){
                    $loi = ceil($phi * 0.8);
                }
                echo '<table id="tbResult">';
                    echo '<tr><td>Phí in</td><td>'.aio_display_money($phiIn).'</td></tr>';
                    echo '<tr><td>Phí giấy <i>('.$soTo.' tờ 65x86)</i></td><td>'.aio_display_money($phiGiay).'</td></tr>';
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