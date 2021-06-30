<?php 
    require_once '../mylib.php';
    require_once '../include.php';
    show_header_include('Tính Giá');
?>

<body class="page-print">
   <div class="container">
        <h1>Tính Giá Tờ Rơi</h1>
                                
       
        <div class="row">
        <div class="col-sm-3 col-xs-12">
        <?php                        
            $size= "a4";
            $soCon = 0;
            $quantity = 1000;
            $paper = 'C150';
            $can = false;
            $gap = false;            
            $heSoLoi = 0.5;
            $mat = 2;
            $mau=4;
            $chiDinhIn = 'innhanh';
            
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
             if(isset($_REQUEST['cbxGap'])){
                $gap = $_REQUEST['cbxGap'];
            }          
            if(isset($_REQUEST['heSoLoi'])){
                $heSoLoi = $_REQUEST['heSoLoi'];
            }
            if(isset($_REQUEST['mat'])){
                $mat = $_REQUEST['mat'];
            }
            if(isset($_REQUEST['mau'])){
                $mau = $_REQUEST['mau'];
            }
            if(isset($_REQUEST['chiDinhIn'])){
                $chiDinhIn = $_REQUEST['chiDinhIn'];
            }
        ?>
        <form id="frmBrochure">
            <label>Kích thước: </label>
            <select name="slSize">
                <option value="a4" <?php if($size=='a4') echo "selected"; ?> >A4</option>
                <option value="a3" <?php if($size=='a3') echo "selected"; ?> >A3</option>
                <option value="a5" <?php if($size=='a5') echo "selected"; ?> >A5</option>                
            </select>  <br />
            <!-- - Hoặc số con trên 1 tờ A3 <input type="text" name="txtSoCon" value="<?php if($soCon!=0) echo $soCon; ?>" /> <br />-->
            
            <label>Số thành phẩm: </label>
            <input type="numeric" name="txtQuantity" value="<?php if($quantity!=0) echo $quantity; ?>" />  <br />
            
            <label>Loại giấy:</label>
            <select name="slPaper">
                <option value="C150" <?php if($paper=='C150') echo "selected"; ?> >C150</option>
                <option value="C200" <?php if($paper=='C200') echo "selected"; ?> >C200</option>
                <option value="C250" <?php if($paper=='C250') echo "selected"; ?> >C250</option>
                <option value="C300" <?php if($paper=='C300') echo "selected"; ?> >C300</option>                                
            </select>  <br />
            
            <label>In mấy mặt:</label>
            <select name="mat">
                <option value="2" <?php if($mat==2) echo "selected"; ?> >2 mặt</option>   
                <option value="1" <?php if($mat==1) echo "selected"; ?> >1 mặt</option>                             
            </select>  <br />

            <label>In mấy màu:</label>
            <select name="mau">
                <option value="4" <?php if($mau==4) echo "selected"; ?> >4 màu</option>   
                <option value="1" <?php if($mau==1) echo "selected"; ?> >1 màu</option>                             
            </select>  <br />
            
            <label>Cán màng?</label>
            <input type="checkbox" name="cbxCan" <?php if($can) echo "checked"; ?> /> <br />
            
            <label>Cấn gấp?</label>
            <input type="checkbox" name="cbxGap" <?php if($gap) echo "checked"; ?> /> <br />
            <hr/>                        
            <label><b>Loại In</b></label>
            <select name="chiDinhIn">
                <option value="innhanh" <?php if($chiDinhIn == 'innhanh') echo "selected"; ?> >In Nhanh</option>
                <option value="inoffset" <?php if($chiDinhIn == 'inoffset') echo "selected"; ?> >In Offset</option>
            </select> <br />
            

            <label>Hệ số lời</label>
            <select name="heSoLoi">
                <option value="0.3" <?php if($heSoLoi == 0.3) echo "selected"; ?> >0.3</option>
                <option value="0.4" <?php if($heSoLoi == 0.4) echo "selected"; ?> >0.4</option>
                <option value="0.5" <?php if($heSoLoi == 0.5) echo "selected"; ?> >0.5</option>
                <option value="0.6" <?php if($heSoLoi == 0.6) echo "selected"; ?> >0.6</option>
                <option value="0.7" <?php if($heSoLoi == 0.7) echo "selected"; ?> >0.7</option>
                <option value="0.8" <?php if($heSoLoi == 0.8) echo "selected"; ?> >0.8</option>
            </select> <br />
             
            <input type="submit" name="btnSubmit" /><br />
            
        </form>
        </div>
            
        <div class="col-sm-9 col-xs-12">
            <h3>Kết Quả</h3>
        <?php                 
            if(isset($_REQUEST['btnSubmit'])){
                $phi = $phiIn = $phiGiay = $phiCan = $phiGap = $phiGiaCong = 0;
                
                 if($chiDinhIn=='innhanh'){    // in nhanh                 
                     
                     $soTo65x86 = round($quantity/8); // neu la A4
                     if($size=='a3'){
                        $soTo65x86 = round($quantity/4);
                     }else if($size=='a5'){
                        $soTo65x86 = round($quantity/16);
                     }
                     $phiGiay = tinhGiaGiay($soTo65x86, $paper);

                     $click=$quantity*2; // A4
                    if($size=="a3"){
                         $click = $quantity *4;
                     }else if($size=="a5"){
                         $click = $quantity;
                     }
                     if($mat==1){
                        $click = $click/2;
                     }
                     $phiIn = $click*660;// 4 mau
                     if($mau==1){
                        $phiIn = $click*240; // trang den
                     }
                     
                     if($can){
                         if($quantity <= 50){
                             $phiCan = 50000;
                         }else{
                            $phiCan = 100000;
                            if($mat==2){
                                $phiCan = 200000;
                            }  
                         }
                     }
                     if($gap){
                         if($quantity <= 50){
                             $phiGap = 50000;
                         }else{
                            $phiGap = 150000;                            
                         }
                     }

                     $phiGiaCong = $phiCan + $phiGap;
                     if($quantity>=20){
                         $phiGiaCong +=30000; // cat
                     }
                     $phi = $phiIn +$phiGiay +$phiGiaCong;
                     $loi = $phi * $heSoLoi;
                     $bao = $phi + $loi;
                     echo '<i>In nhanh</i>';
                     echo '<table id="tbResult">';
                        echo '<tr  class="red"><td>Giá báo</td><td><b>'.display_number($bao).'</b></td><td></td></tr>';
                        echo '<tr><td>Đơn giá</td><td><i>'.display_number($bao/$quantity).'</i></td><td></td></tr>';
                        echo '<tr class="split"><td colspan="3"></td></tr>';
                     
                     if(is_login()){
                        echo '<tr><td>Giá In (Click)</td><td>'.display_number($phiIn).'</td><td>('.$click.' click)</td></tr>';
                        echo '<tr><td>Giá Giấy</td><td>'.display_number($phiGiay).'</td><td>('.$soTo65x86.' tờ 65x86 giấy ' .$paper.')</td></tr>';
                        echo '<tr><td>Phí gia công</td><td>'. display_number($phiGiaCong).'</td><td></td></tr>';                        
                     
                        
                        echo '<tr class="split"><td colspan="3"></td></tr>';                     
                        echo '<tr><td>Giá gốc</td><td><b>'.display_number($phi).'</b></td><td></td></tr>';
                        echo '<tr><td>Lời </td><td>'.display_number($loi).'</td><td></td></tr>';                    
                                     
                     }
                    echo '</table>';  
                     
                 }else{ // in offset

                        if($soCon == 0){
                            if($size=="a4"){
                                $soCon = 2;
                            }else if($size=="a5"){
                                $soCon = 4;
                            }
                        }                                
                        $soTo = 0;
                        if($size!="a3"){
                            $soToA3 = ($quantity / $soCon) + 50;                
                            $phiIn = tinhGiaInOffset('32x43', $soToA3, $mau);
                            $baiIn = '1 bài 32x43';
                            if($soToA3>6000){
                                $phiIn = tinhGiaInOffset('43x65', ($soToA3/2), $mau);
                                $baiIn = '1 bài 43x65 in '.($soToA3/2).' lượt';
                            }
                            if($soToA3>12000){
                                $phiIn = tinhGiaInOffset('65x86', ($soToA3/4), $mau);
                                $baiIn = '1 bài 65x86 in '.($soToA3/4).' lượt';
                            }

                            $soTo = ceil($soToA3/4); // 65x86
                                                        
                        }else{
                            $soTo_6543 = ($quantity / 2) + 50;                
                            $phiIn = tinhGiaInOffset('32x43', $soTo_6543, $mau);
                            $soTo = ceil($soTo_6543/2);
                            $baiIn = '1 bài 65x43';

                            if($soTo_6543>6000){
                                $phiIn = tinhGiaInOffset('65x86', $soTo, $mau);
                                $baiIn = '1 bài 65x86 in '.($soTo).' lượt';
                            }

                            
                        }
                        
                        $phiGiay = round(tinhGiaGiay($soTo, $paper), -3);

                        $phiGiaCong = 40000;
                        if($can){
                            $phiCan = ($soTo * 0.65 * 0.86 * 2000); 
                            $phiCan *= 2; // can 2 mat
                            $phiCan = round($phiCan,-3);
                            $phiGiaCong+= $phiCan;
                        }
                        if($gap){
                            $phiGiaCong +=300000;
                        }
                        $phiShip = 50000;

                        $phi = $phiIn + $phiGiay + $phiGiaCong +$phiShip;                

                        $loi = ceil($phi * $heSoLoi);
                              
                echo '<i>In offset</i>';
                echo '<table id="tbResult">';
                    echo '<tr  class="red"><td>Giá báo</td><td><b>'.display_number(($phi + $loi)).'</b></td><td></td></tr>';
                    echo '<tr><td>Đơn giá</td><td><i>'.display_number((($phi + $loi)/$quantity)).'</i></td><td></td></tr>';
                     
                    if(is_login()){
                        echo '<tr class="split"><td colspan="3"></td></tr>';
                        echo '<tr><td>Phí in</td><td>'.display_number($phiIn).'</td><td>('.$baiIn.')</td></tr>';
                        echo '<tr><td>Phí giấy</td><td>'.display_number($phiGiay).'</td><td>('.$soTo.' tờ 65x86 giấy '.$paper.')</td></tr>';
                        echo '<tr><td>Phí gia công</td><td>'. display_number($phiGiaCong).'</td><td></td></tr>';
                        echo '<tr><td>Phí ship</td><td>'.display_number($phiShip).'</td><td></td></tr>';
                        
                        echo '<tr class="split"><td colspan="3"></td></tr>';
                        echo '<tr><td>Giá gốc</td><td><b>'.display_number($phi).'</b></td><td></td></tr>';
                        echo '<tr><td>Lời </td><td>'.display_number($loi).'</td><td></td></tr>';                    
                    }
                echo '</table>';   
                 }
            }
                    
        ?>
        </div>
    </div>
</div>
</body>
