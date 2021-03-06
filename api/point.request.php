<?php
require_once('user.class.php');
require_once('site.class.php');
require_once('point.class.php');
require_once('course.class.php');

if($action[1]=='show')
{
	$nowUser=User::show(Site::getSessionUid());
	$nowRoleId=$nowUser[0]['roleId'];
	$pid=getRequest('pid');
	$response=Point::show($pid);

	if($nowRoleId==1||($nowRoleId==2 && Course::findOwner($pid)==$nowUser))
		$flag=1;
	else 
		$flag=0;


	if($flag==1||$response['visibility']==true)
	{
		if($response==false)
		{
			handle(ERROR_SYSTEM.'04');
		}
		else 
		{
			if($response['visibility']==1)
				$response['visibility']=true;
			else 
				$response['visibility']=false;
	

			$response['pid']=(int)$response['pid'];
			$response['createTime']=(int)$response['createTime'];
			$response['updateTime']=(int)$response['updateTime'];
			$response['importance']=(int)$response['importance'];
			$response['name']=urldecode($response['name']);
			$response['content']=urldecode($response['content']);
			$response['courseId']=(int)$response['courseId'];
			$response['order']=(int)$response['order'];

			
			handle('0000'.json_encode($response));
		}
	}
	else 
		handle(ERROR_PERMISSION.'04');
}

if($action[1]=='list')
{
	$response=Point::list(getRequest('ownerId'),getRequest('name'));
	foreach ($response as &$i) 
	{
			$i['pid']=(int)$i['pid'];
			$i['createTime']=(int)$i['createTime'];
			$i['updateTime']=(int)$i['updateTime'];
			$i['importance']=(int)$i['importance'];
			$i['name']=urldecode($i['name']);
			$i['content']=urldecode($i['content']);
			$i['courseId']=(int)$i['courseId'];
			$i['order']=(int)$i['order'];
			if($i['visibility']==1)
				$i['visibility']=true;
			else 
				$i['visibility']=false;
	}

	handle('0000'.json_encode($response));
	
}


if(!checkAuthority())
	handle(ERROR_PERMISSION.'01');
$nowUser=User::show(Site::getSessionUid());
$nowRoleId=$nowUser[0]['roleId'];


if ($action[1]=='new') 
{
	if($nowRoleId>2)
		handle(ERROR_PERMISSION.'01');

	$newPoint=new Point;
	if( ($response=$newPoint->create(getRequest('importance'),getRequest('name'),getRequest('content'),getRequest('courseId'))) !=0)
	{
		handle('0000{"cid":'.$response.'}');
	}
	else
	{
		handle(ERROR_SYSTEM.'04');
	}
}

if($action[1]=='renew')
{

	if($nowRoleId>2)
		handle(ERROR_PERMISSION.'01');

	$response=Point::modify(getRequest('pid'),getRequest('importance'),getRequest('name'),getRequest('content'),getRequest('courseId'),getRequest('order'),getRequest('visibility'));

	if($response==false)
	{
		handle(ERROR_SYSTEM.'04');
	}
	else 
	{
		handle('0000');
	}

}
if($action[1]=='delete')
{
	if($nowRoleId>2)
		handle(ERROR_PERMISSION.'01');

	$response=Point::delete(getRequest('pid'));
	if($response==false)
	{
		handle(ERROR_SYSTEM.'04');
	}
	else 
	{
		handle('0000');
	}
}

handle(ERROR_INPUT.'04');

?>