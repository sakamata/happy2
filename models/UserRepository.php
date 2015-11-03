<?php

class UserRepository extends DbRepository
{

	public function insert($user_id, $password, $user_name)
	{
		$password = $this->hashPassword($password);
		$now = new DateTime();
		$img =  "dummy.png";
		$nowPt = AdminSettingRepository::userDefaultPoint;
		$ip = $_SERVER['REMOTE_ADDR'];
		$host = gethostbyaddr($ip);

		$sql = "
			INSERT INTO tbus (usId,usPs,usName,usImg,nowPt,ip,host,regDate)
			VALUES(:user_id, :password, :user_name, '$img', '$nowPt', '$ip', '$host', :regDate)
		";

		$stmt = $this->execute($sql, array(
			':user_id' => $user_id,
			':password' => $password,
			':user_name' => $user_name,
			':regDate' => $now->format('Y-m-d H:i:s'),
		));
	}


	public function hashPassword($password)
	{
		return sha1($password . 'zyx7532cba');
	}

	public function fetchByUserName($user_id)
	{
		$sql = "SELECT * FROM tbus WHERE usId = :user_id";

		return $this->fetch($sql, array(':user_id' => $user_id));
	}

	public function isUniqueUserName($user_id)
	{
		$sql = "SELECT COUNT(usNo) as count FROM tbus WHERE usId = :user_id";

		$row = $this->fetch($sql, array(':user_id' => $user_id));
		if ($row['count'] === '0') {
			return true;
		}

		return false;
	}

	public function fetchAllFollowingsByUserId($user_id)
	{
		$sql = "
			SELECT u.*
			FROM user u
				LEFT JOIN following f ON f.following_id = u.id
			WHERE f.user_id = :user_id
		";

		return $this->fetchAll($sql, array(':user_id' => $user_id));
	}

}
