<?php
class AdminRepository extends DbRepository
{
	public function fetchByAdminUserName($usId)
	{
		$sql = "SELECT * FROM adtbus WHERE usId = :usId";

		return $this->fetch($sql, array(':usId' => $usId));
	}

	// PassWordを非表示とするため別メソッドとした
	public function fetchAlltbus($limit, $offset)
	{
		// ***危険*** 変数を直接クエリに入れている（mysqlの limitバグの為）
		// ***ToDo*** ここにPOSTされた値を拾った時点で型確認checkを書く
		$sql = "
			SELECT usNo, usId, usName, usImg, nowPt, ip, host, regDate from tbus
			LIMIT $limit
			OFFSET $offset
		";

		return $this->fetchAll_bindParam($sql, array(
			':limit' => $limit,
			':offset' => $offset
		));
	}


	public function fetchAllTable($tableName, $limit, $offset)
	{
		$sql = "
			SELECT * from $tableName
			LIMIT $limit
			OFFSET $offset
		";

		return $this->fetchAll_bindParam($sql, array(
			':tableName' => $tableName,
			':limit' => $limit,
			':offset' => $offset,
		));
	}


	public function tableCount($tableName)
	{
		$sql = "
			SELECT COUNT(*) AS $tableName from $tableName
		";

		return $this->fetch($sql, array(
		));
	}

}
