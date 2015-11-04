<?php

class StatusRepository extends DbRepository
{

// ***ToDo*** メイン画面でのUserの基本情報を呼び出す
// ***ToDo*** 引数追加, $order, $limit 等
public function fetchUserStatus($usNo)
{
	$sql ="
		SELECT usNo, usId, usName, usImg, nowPt FROM tbus
		ORDER BY usNo = :usNo DESC, usNo ASC
	";
	return $this->fetchAll($sql, array(':usNo' => $usNo));

}


	// 引数 $user_id : str 単数配列で来る。 $user['usNo']
	public function fetchAllPersonalArchivesByUserId($user_id)
	{
		$sql = "
			SELECT a.*, u.user_name
			FROM status a
				LEFT JOIN user u ON a.user_id = u.id
				LEFT JOIN following f ON f.following_id = a.user_id
					AND f.user_id = :user_id
			WHERE f.user_id = :user_id OR u.id = :user_id
			ORDER BY a.created_at DESC
		";

		return $this->fetchAll($sql, array(':user_id' => $user_id));
	}

	public function fetchAllByUserId($user_id)
	{
		$sql = "
			SELECT a.*, u.user_name
			FROM status a
				LEFT JOIN user u ON a.user_id = u.id
			WHERE u.id = :user_id
			ORDER BY a.created_at DESC
		";

		return $this->fetchAll($sql, array(':user_id' => $user_id));

	}

	public function fetchByIdAndUserName($id, $user_name)
	{
		$sql = "
			SELECT a.*, u.user_name
			FROM status a
				LEFT JOIN user u ON u.id = a.user_id
			WHERE a.id = :id
				AND u.user_name = :user_name
		";

		return $this->fetch($sql, array(
			':id' => $id,
			':user_name' => $user_name,
		));

	}

}
