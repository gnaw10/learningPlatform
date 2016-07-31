<?php 
	require_once('site.class.php');
	require_once('user.class.php');
	require_once('role.class.php');
	switch ($action[1]) {
		case 'globalData':
			global $pdo;
			$sqlSite=$pdo->prepare('SELECT * FROM `user`;');
			$sqlSite->execute();
			$registerNumner=$sqlSite->rowCount();
			handle('0000{"siteName":"'.SITENAME.'","isOpen":'.ISOPEN.',"canRegister":'.CANREGISTER.',"numberOfRegisteredUser":'.$registerNumner.'}');
			
		break;
		
		case 'sessionData':
			$sessionUid=Site::getSessionUid();	
			if($sessionUid==0)
				handle('0000{"signin":false}');
			else 
				{
					$response=User::show($sessionUid);
					//var_dump($response);
					$roleName=Role::find($response[0]['roleId']);
					if($roleName==false)
						handle(ERROR_SYSTEM.'00');
					handle('0000{"signin":true,"user":'.json_encode($response[0]).'}');
				}
		break;
		default:
			handle(ERROR_INPUT.'00');
		break;
	}
?>
