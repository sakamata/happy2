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


	// 引数 $usId : str 単数配列で来る。 $user['usNo']
	public function fetchAllPersonalArchivesByUserId($usId)
	{
		$sql = "
			SELECT a.*, u.usName
			FROM status a
				LEFT JOIN user u ON a.usId = u.id
				LEFT JOIN following f ON f.following_id = a.usId
					AND f.usId = :usId
			WHERE f.usId = :usId OR u.id = :usId
			ORDER BY a.created_at DESC
		";

		return $this->fetchAll($sql, array(':usId' => $usId));
	}

	public function fetchAllByUserId($usId)
	{
		$sql = "
			SELECT a.*, u.usName
			FROM status a
				LEFT JOIN user u ON a.usId = u.id
			WHERE u.id = :usId
			ORDER BY a.created_at DESC
		";

		return $this->fetchAll($sql, array(':usId' => $usId));

	}

	public function fetchByIdAndUserName($id, $usName)
	{
		$sql = "
			SELECT a.*, u.usName
			FROM status a
				LEFT JOIN user u ON u.id = a.usId
			WHERE a.id = :id
				AND u.usName = :usName
		";

		return $this->fetch($sql, array(
			':id' => $id,
			':usName' => $usName,
		));

	}

}
