<?php 
	require_once './manager/mylib.php';

	if(is_login()){        
		//header("Location: /php/database");
        //die;
    }

    if(isset($_REQUEST['btnLogin'])){
        $name = htmlspecialchars($_REQUEST['name']);
        $pass = htmlspecialchars($_REQUEST['pass']);
        $user = user_login($name, $pass);
        if($user){
            $_SESSION['user'] = $user;
            //var_dump($user);die;
            if($user['role']==ROLE_ADMIN){
                header('Location: /manager/admin');                
            }else if($user['role']==ROLE_SALE){
                header('Location: /manager/admin/sale.php');                
            }else if($user['role']==ROLE_DELIVERY){
                if(is_mobile()){
                    header('Location: /manager/delivery/mobile.php');                
                }else{
                    header('Location: /manager/delivery');                
                }
            }else{
                if(is_mobile()){
                    header('Location: /manager/mytask/mobile.php');                
                }else{
                    header('Location: /manager/mytask');                
                }
            }
            die();
        }else{
            $_SESSION['error']='Vui lòng kiểm tra lại tên đăng nhập hoặc mật khẩu';
        }
    }
?>

<body id="page-items">

    <div class="container">
        <div id="row-1" class="row">
            <div class="col-sm-4 col-xs-12">
                <h1><a href="./">SNH CRM</a></h1>
            </div>
            <div class="col-sm-8 col-xs-12">
                
            </div>
        </div>            
             
        <div class="row">
        
            <div id="main-content" class="col-sm-12 col-xs-12">
                <form id="frmLogin" method="post">                
                    <input type="text" name="name" placeholder="Username"/><br />
                    <input type="password" name="pass" placeholder="Password"/><br />
                    <input type="submit" name="btnLogin" value="Login" />
                </form>
            </div>
        
            <?php 
                if(isset($_SESSION['error'])){
                    echo '<div id="s-errors">'.$_SESSION['error'].'</div>';
                }
            ?>
        </div>
    </div>

</body>