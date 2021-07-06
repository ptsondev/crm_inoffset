<?php

require_once '../include.php';

require_once '../mylib.php';
show_header_include('Timeline');
?>




<?php 

	$PID = $_REQUEST['PID'];
	$timeline = getTimelineByPID($PID);	

	//var_dump($timeline);die;
	if(count($timeline)<1){

		die('Đơn "Đã Ký" mới hiện timeline. Vui lòng xem lại status trước');

	}

?>



<form id="f-timeline">

	<div class="item">

		<?php 

			$class = ($timeline[0]['finish']==1) ? 'done':'doing';

			echo '<span class="num '.$class.'">1</span>';

		?>

		<label>Tiếp Nhận Đơn Hàng</label>

		<?php 

			$selected = isset($timeline[0]['UID'])? $timeline[0]['UID']:4;	// nếu chưa được set thì set default theo uid 4: Hà

			echo '<select id="slNhanDon" tid="'.$timeline[0]['TID'].'">';

				display_select_users($selected);

			echo '</select>';

		?>
		<input type="text" class="text-full" id="noteSale" value="<?php echo $timeline[0]['note']; ?>"/>
		<span class="ddate"><?php if($timeline[0]['finish']==1)  echo date('h:i:s d/m/Y', $timeline[0]['created']);?></span>
		
		
	</div>



	

	<div class="item">

		<?php 

			$disable = count($timeline)>0? '':'disabled="disabled"'; 

			$class = ($timeline[1]['finish']==1) ? 'done':'doing';

			echo '<span class="num '.$class.'">2</span>';

		?>

		<label>Xử Lý File</label>

		<?php 

			$selected = !empty($timeline[1]['UID'])? $timeline[1]['UID']:1;	// nếu chưa được set thì set default theo uid 1: Sơn

			echo '<select id="slXuLyFile" '.$disable.'  tid="'.$timeline[1]['TID'].'" >';

				display_select_users($selected);

			echo '</select>';

		?>
		<input type="text" class="text-full" id="noteDesign" value="<?php echo $timeline[1]['note']; ?>"/>
		<span class="ddate"><?php if($timeline[1]['finish']==1)   echo date('h:i:s d/m/Y', $timeline[1]['created']);?></span>

		
	</div>



	

	<div class="item">

		<?php 

			$disable = count($timeline)>1? '':'disabled="disabled"';

			$class = ($timeline[2]['finish']==1) ? 'done':'doing';

			echo '<span class="num '.$class.'">3</span>';

		?>

		<label>In Ấn</label>

		<?php 

			$selected = !empty($timeline[2]['UID'])? $timeline[2]['UID']:12;			// nếu chưa được set thì set default theo uid 12: Hải

			echo '<select id="slIn" '.$disable.'  tid="'.$timeline[2]['TID'].'" >';

				display_select_users($selected);

			echo '</select>';

		?>
		<input type="text" class="text-full" id="notePrint" value="<?php echo $timeline[2]['note']; ?>"/>
		<span class="ddate"><?php if($timeline[2]['finish']==1)  echo date('h:i:s d/m/Y', $timeline[2]['created']);?></span>
		
	</div>



	

	<div class="item">

		<?php 

			$disable = count($timeline)>2? '':'disabled="disabled"';

			$class = ($timeline[3]['finish']==1) ? 'done':'doing';

			echo '<span class="num '.$class.'">4</span>';

		?>

		<label>Gia Công</label>

		<?php 

			$selected = !empty($timeline[3]['UID'])? $timeline[3]['UID']:7;			// nếu chưa được set thì set default theo uid 7: Tài

			echo '<select id="slGiaCong" '.$disable.'  tid="'.$timeline[3]['TID'].'" >';

				display_select_users($selected);

			echo '</select>';

		?>
		<input type="text" class="text-full" id="noteProcess" value="<?php echo $timeline[3]['note']; ?>"/>
		<span class="ddate"><?php if($timeline[3]['finish']==1)  echo date('h:i:s d/m/Y', $timeline[3]['created']);?></span>
		
	</div>



	

	<div class="item">

		<?php 

			$disable = count($timeline)>3? '':'disabled="disabled"';

			$class = ($timeline[4]['finish']==1) ? 'done':'doing';

			echo '<span class="num '.$class.'">5</span>';

		?>

		<label>Giao Hàng</label>

		<?php 

			$selected = !empty($timeline[4]['UID'])? $timeline[4]['UID']:1;	// nếu chưa được set thì set default theo uid 1: Sơn

			echo '<select id="slGiaoHang" '.$disable.'  tid="'.$timeline[4]['TID'].'" >';

				display_select_users($selected);

			echo '</select>';

		?>
		<input type="text" class="text-full" id="noteDelivery" value="<?php echo $timeline[4]['note']; ?>"/>
		<span class="ddate"><?php if($timeline[4]['finish']==1)  echo date('h:i:s d/m/Y', $timeline[4]['created']);?></span>
		
	</div>


	<input type="button" value="Save" id="btnUpdateTimeline" pid="<?php echo $PID;?>" />
	<div id="result"></div>
	
	

</form>

