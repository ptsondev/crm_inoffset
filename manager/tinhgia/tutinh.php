<?php 
    require_once '../mylib.php';
    require_once '../include.php';
    show_header_include('Tính Giá');
?>

<body class="page-print">
   <div class="container">
        <h1>Tự Tính</h1>             
        <div id="main-content">   
            
            <?php                        
                $to=1;
                $mau=4;
                $kho='65x86';
                $giay='C300';
                if(isset($_REQUEST['txtTo'])){
                    $to = $_REQUEST['txtTo'];
                }
                if(isset($_REQUEST['sKho'])){
                    $kho = $_REQUEST['sKho'];
                }
                if(isset($_REQUEST['sGiay'])){
                    $giay = $_REQUEST['sGiay'];
                }
                if(isset($_REQUEST['sMau'])){
                    $mau = $_REQUEST['sMau'];
                }

            ?>
        <form id="frTuTinh">
              <label>Khổ In:</label>
            <select name="sKho">
                <option value="32x43" <?php if($kho=='32x43') echo "selected"; ?> >32x43</option>
                <option value="43x65" <?php if($kho=='43x65') echo "selected"; ?> >43x65</option>
                <option value="65x86" <?php if($kho=='65x86') echo "selected"; ?> >65x86</option>
                
                <option value="54x79" <?php if($kho=='54x79') echo "selected"; ?> >54x79</option>
                <option value="79x109" <?php if($kho=='79x109') echo "selected"; ?> >79x109</option>                
            </select>  <br />
            
            
            <label>Số Tờ In: </label>
            <input type="numeric" name="txtTo" value="<?php if($to!=0) echo $to; ?>" />  <i>(nhớ bù hao)</i><br />
                      
            <label>Loại Giấy:</label>
            <select name="sGiay">
                <option value="C100" <?php if($giay=='C100') echo "selected"; ?> >C100</option>
                <option value="C115" <?php if($giay=='C115') echo "selected"; ?> >C115</option>
                <option value="C150" <?php if($giay=='C150') echo "selected"; ?> >C150</option>
                <option value="C200" <?php if($giay=='C200') echo "selected"; ?> >C200</option>
                <option value="C250" <?php if($giay=='C250') echo "selected"; ?> >C250</option>
                <option value="C300" <?php if($giay=='C300') echo "selected"; ?> >C300</option>
                <option value="F80" <?php if($giay=='F80') echo "selected"; ?> >F80</option>
                <option value="F100" <?php if($giay=='F100') echo "selected"; ?> >F100</option>
                <option value="F140" <?php if($giay=='F140') echo "selected"; ?> >F140</option>
                <option value="F230" <?php if($giay=='F230') echo "selected"; ?> >F230</option>
                <option value="I300" <?php if($giay=='I300') echo "selected"; ?> >I300</option>
                <option value="I350" <?php if($giay=='I350') echo "selected"; ?> >I350</option>
                <option value="B300" <?php if($giay=='B300') echo "selected"; ?> >B300</option>
                <option value="B350" <?php if($giay=='B350') echo "selected"; ?> >B350</option>                
            </select>  <br />
            
            <label>In màu</label>
             <select name="sMau">
                <option value="1" <?php if($mau==1) echo "selected"; ?> >1 màu</option>
                 <option value="4" <?php if($mau==4) echo "selected"; ?> >4 màu</option>                
            </select>  <br />
            
            <input type="submit" name="btnSubmit" value="Tính" /><br /><hr />
          </form>  
            
            <?php 
                $giaGiay = $soTo65x86 = $soTo79x109 = 0;         
                if($kho=='32x43' || $kho=='43x65' || $kho=='65x86'){
                    if($kho=='32x43'){
                        $soTo65x86 = $to/4;
                    }else if($kho=='43x65'){
                        $soTo65x86 = $to/2;                        
                    }else{
                        $soTo65x86 = $to;
                    }
                    $giaGiay = tinhGiaGiay($soTo65x86, $giay, '65x86');
                    echo '<i>'.$soTo65x86.' tờ '.$giay.'_65x86</i>';
                }else if($kho=='54x79' || $kho=='79x109'){
                    if($kho='54x79'){
                        $soTo79x109=$to/2;
                    }else{
                        $soTo79x109=$to;
                    }
                    $giaGiay = tinhGiaGiay($soTo79x109, $giay, '79x109');
                    echo '<i>'.$soTo79x109.' tờ '.$giay.'_79x109</i>';
                }
                echo '<h4>Giá Giấy: <b>'.display_number($giaGiay).'</b></h4>';
            
                $giaIn = tinhGiaInOffset($kho, $to, $mau);
                echo '<h4>Giá In: <b>'.display_number($giaIn).'</b></h4>';
            ?>    
        </div>
    </div>
</body>

