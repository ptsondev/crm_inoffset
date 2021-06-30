<?php

    require_once '../mylib.php';
    require_once '../include.php';
    show_header_include('Report In Nhanh');
    
    if(!is_login()){        
        header("Location: /");
        die;
    }
    $user = $_SESSION['user'];

/*
    if($user['role']!=ROLE_DESIGN && $user['role']!=ROLE_ADMIN){

        header("Location: /");

        die;    

    }
*/
    display_site_header();

?>

<h3 id="page-title">Report in nhanh</h3>
<?php

    

    
    //echo '<pre>';var_dump($arrPaper);die;
    echo '<div class="fix-height">'; // for freeze 1st row
    echo '<table id="tbCounter">';
    
        echo '<tr class="firstRow">';
            //echo '<td class="CID">CID</td>';
            echo '<th class="PID">PID</th>';
            echo '<th class="filename">Tên File</th>';
            echo '<th class="vtid">Giấy</th>';
            echo '<th class="num">Số Tờ</th>';
            echo '<th class="matin">Mặt In</th>';            
            echo '<th class="click_colors">Click Màu</th>';
            echo '<th class="click_bw">Click B&W</th>';
            echo '<th class="note">Ghi Chú</th>';
			echo '<th class="created">Ngày Đăng</th>';
			if(TRUE){ // nếu là admin mới hiện 3 cột này
				echo '<th class="phi_giay">Phí Giấy</th>';
				echo '<th class="phi_in">Phí In</th>';
				echo '<th class="tong_phi">Tổng Phí</th>';
			}
            echo '<th class="created">Thao Tác</th>';
        echo '</tr>';

        echo '<tr class="lastRow">';
            //echo '<td class="CID"></td>';
            echo '<td class="PID"><input type="number" id="txtPID" /></td>';
            echo '<td class="filename"><input type="text" id="txtFileName" /></td>';
            echo '<td class="vtid">'.getListPaper('slPaper').'</td>';
            echo '<td class="num"><input type="text" id="txtNum" /></td>';
            echo '<td class="matin">
                    <input type="radio" name="rdMatIn" id="rdMatIn" value="2m" checked /> 2
                    <input type="radio" name="rdMatIn" id="rdMatIn" value="1m" /> 1
                </td>';                            
            echo '<td class="click_colors" colspan="2">
                    <input type="radio" name="rdClickColor" id="rdClickColor" value="4M" checked /> Màu 
                    <input type="radio" name="rdClickColor" id="rdClickColor" value="BW" /> Trắng Đen
                </td>';                
            echo '<td class="note"><input type="text" id="txtNote" /></td>';
            echo '<td class="note">'.date('d/m/Y').'</td>';
			//echo '<td class="created"></td>';
			if(TRUE){ // nếu là admin mới hiện 3 cột này
				echo '<td></td><td></td><td></td>';
			}
			echo '<td><input type="submit" id="btnSaveCounter" value="Thêm" /></td>';
            
        echo '</tr>';

        echo renderTableCounter();
    echo '</table>';
    echo '</div>';
?>



</body>

</html>