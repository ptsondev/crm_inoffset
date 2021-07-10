<div class="row">
	<div class="col-sm-6">
		<h3><i class="fas fa-book-open"></i> Thông Tin & Quy Cách</h3>
		<textarea id="p-summary"></textarea>		
	</div>
	<div class="col-sm-6">
		<h3><i class="fas fa-pen"></i> Mô tả thiết kế</h3>
		<textarea id="p-summary-design"></textarea>

		<h3><i class="fas fa-shipping-fast"></i> Thông tin giao hàng</h3>
		<textarea id="p-delivery"></textarea>		
	</div>
</div>



<?php 
    $user = $_SESSION['user'];
    if($user['role']==ROLE_ADMIN){
?>
<div id="admin-note">
	<div class="row">
		<div class="col-sm-6">
			<h3><i class="fas fa-exclamation-triangle"></i> Ghi Chú Riêng Cho Admin</h3>
			<textarea id="p-admin-note"></textarea>
		</div>
	
		<div class="col-sm-6" id="thuchi-region">
			<h3><i class="fas fa-dollar-sign"></i> Thu Chi</h3>
			
					<button id="btnShowThuChi">+ Thêm Mục Thu Chi</button>
					<label>Tổng thu: </label><span class="value" id="tongthu"></span><br/>
					<label>Tổng chi: </label><span class="value"  id="tongchi"></span><br/>
					<label>Lời: </label><span class="value"  id="loilo"></span><br/>			
		</div>	
	</div>
</div>
<?php } ?>


<h3><i class="fas fa-images"></i> Hình Ảnh</h3>
<div class="row">
	<div class="col-sm-2">
		<form id="frmUploadProjectPicture" action="/manager/admin/ajax.php?action=uploadProjectPictures&PID=" method="post" enctype="multipart/form-data">	
			<!-- action của form trên sẽ được override lại bằng js -->
		    <input type="file" name="files[]" multiple id="picture_uploads">		       
		</form>
	</div>
	<div class="col-sm-10">
		<div id="project_pictures"></div>
	</div>
</div>	



<button class="crm_button" id="btnUpdateProject">Save</button>

<div id="showResult"></div>

<script>
jQuery(document).ready(function($){
	$('#picture_uploads').change(function(){
		$("#frmUploadProjectPicture").ajaxSubmit(function(res){
			$('#project_pictures').append(res);
		});
	});
});
</script>