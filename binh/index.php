
<!-- saved from url=(0015)http://in1.dev/ -->
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>SNH - Bình file đánh số trang</title>
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
            $n=8;
            if(isset($_REQUEST['txtNumber'])){
                $n = $_REQUEST['txtNumber'];
            }
            $bkr=0;
            if(isset($_REQUEST['cbxBiaKhacRuot']) && $_REQUEST['cbxBiaKhacRuot']==1){
                $bkr=1;
            }
            $tro = 'tayKe';
            if(isset($_REQUEST['rdTro'])){
                $tro = $_REQUEST['rdTro'];
            }
        ?>
        <form>
            Số trang: <input type="text" name="txtNumber" value="<?php echo $n; ?>"/><br />
            Bìa khác ruột? <input type="checkbox" name="cbxBiaKhacRuot" value="1" <?php if($bkr){echo 'checked'; }?>/> <br />
            Kieu tro: <input type="radio" name="rdTro" value="tayKe" <?php if($tro=='tayKe') echo 'checked'; ?> /> Tro tay ke 
            <input type="radio" name="rdTro" value="dauNhip" <?php if($tro=='dauNhip') echo 'checked'; ?>/> Tro dau nhip
            <input type="radio" name="rdTro" value="AB" <?php if($tro=='AB') echo 'checked'; ?>/> AB <br />
            <input type="submit" name="Submit" /><br />
        </form>
        
        <?php 
                
            if(isset($_REQUEST['txtNumber'])){
                $n = $_REQUEST['txtNumber'];
                if($n%4!=0){
                    echo '<div class="error">N phải chia hết cho 4</div>';
                    return;
                    die;
                }                
                Binh($n, $bkr, $tro);
            }
        ?>
         
    </body>        
</html>

<?php
function Ve6586($max, $min, $tro='tayKe'){
    if($tro=='tayKe'){
        echo '<div class="to6586">';
        echo '<div class="r">';
            echo '<div class="a4">'.$max.'</div><div class="a4">'.$min.'</div> <div class="a4">'.($min+1).'</div><div class="a4">'.($max-1).'</div>';
        echo '</div>';
        echo '<div class="r">';
            echo '<div class="a4">'.($max-2).'</div><div class="a4">'.($min+2).'</div> <div class="a4">'.($min+3).'</div><div class="a4">'.($max-3).'</div>';
        echo '</div>';
        echo '</div>';
    }else if($tro=='dauNhip'){
        echo '<div class="to6586 nhip">';
        echo '<div class="r">';
            echo '<div class="a4">'.$max.'</div><div class="a4">'.$min.'</div> <div class="a4">'.($max-2).'</div><div class="a4">'.($min+2).'</div>';
        echo '</div>';
        echo '<div class="r">';
            echo '<div class="a4">'.($max-1).'</div><div class="a4">'.($min+1).'</div> <div class="a4">'.($max-3).'</div><div class="a4">'.($min+3).'</div>';
        echo '</div>';
        echo '</div>';
    }else 
       
    }
}      
    
    
function VeAB($max, $min){    
    while($max > $min){   
         /*
            16 1 14 3
            12 5 10 7

            2 15 4 13
            6 11 8 9
        */
        
        echo '<div class="to6586 AB">';
            echo '<div class="r">';
                echo '<div class="a4">'.$max.'</div><div class="a4">'.$min.'</div><div class="a4">'.($max-2).'</div><div class="a4">'.($min+2).'</div>';                
            echo '</div>';
            echo '<div class="r">';
                echo '<div class="a4">'.($max-4).'</div><div class="a4">'.($min+4).'</div><div class="a4">'.($max-6).'</div><div class="a4">'.($min+6).'</div>';
            echo '</div>';
        echo '</div>';
        
        
        echo '<div class="to6586 AB">';
            echo '<div class="r">';
                echo '<div class="a4">'.($min+1).'</div><div class="a4">'.($max-1).'</div><div class="a4">'.($min+3).'</div><div class="a4">'.($max-3).'</div>';                
            echo '</div>';
            echo '<div class="r">';
                echo '<div class="a4">'.($min+5).'</div><div class="a4">'.($max-5).'</div><div class="a4">'.($min+7).'</div><div class="a4">'.($max-7).'</div>';
            echo '</div>';
        echo '</div>';
        $max-=8;
        $min+=8;
    }
}
function Ve6543($max, $min){
    echo '<div class="to6543">';
    echo '<div class="r">';
        echo '<div class="a4">'.$max.'</div><div class="a4">'.$min.'</div>';    
    echo '</div>';   
    echo '<div class="r">';
        echo '<div class="a4">'.($min+1).'</div><div class="a4">'.($max-1).'</div>';     
    echo '</div>';
    echo '</div>';
}  

function Binh($n=8, $bkr=0, $tro='tayKe'){
    $max = $n; $min=1;
    if($bkr){
        Ve6543($max, $min);
        $max-=2;
        $min+=2;
    }
    if($tro=='AB'){
        VeAB($max, $min);
    }else{
        while($max-4>$min){        
            Ve6586($max, $min, $tro);
            $max-=4;
            $min+=4;
            if($max-$min==3){
                Ve6543($max, $min);
            }
        }
    }
}
?>
    
    
