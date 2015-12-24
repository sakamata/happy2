<?php

class StatusRepository extends DbRepository
{

	public function calcStatus()
	{
		$sql ="
			SELECT
				MAX(calcTime) AS lastCalcTime,
				COUNT(calcNo) AS calcCount
				FROM tbcalctime
		";
		return $this->fetch($sql, array());
	}

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

	public function testUsersArrayFollowUsers($usNo, $lastCalcTime)
	{
		$sql = "
			SELECT
				master.usNo,
				master.usId,
				master.usName,
				master.usImg,
				master.nowPt,
				gvnTable.allClkSum,
				IFNULL(gvnTable2.toMeClkSum, 0) AS toMeClkSum
				FROM tbus
				AS master
			LEFT JOIN(
				SELECT
					usNo,
					sum(seClk) AS allClkSum
					FROM tbgvn
					WHERE
						tbgvn.usNo
						IN(
							SELECT
								followingNo
								FROM tbfollow
								WHERE usNo = :usNo
						)
					GROUP BY usNo
			)
			AS gvnTable
			ON master.usNo = gvnTable.usNo
			LEFT JOIN(
				SELECT usNo, SUM(seClk) AS toMeClkSum
					FROM tbgvn
					WHERE seUs = :usNo
						AND dTm between :lastCalcTime
						AND now()
						GROUP BY usNo
			)
			AS gvnTable2
			ON master.usNo = gvnTable2.usNo
			LEFT JOIN(
				SELECT followNo, usNo, followingNo
					FROM tbfollow
					WHERE usNo = :usNo
			)
			AS followTable
			ON master.usNo = followTable.followingNo
			WHERE
				master.usNo
				IN(
					SELECT
						followingNo
						FROM tbfollow
						WHERE usNo = :usNo
				)
			AND master.usNo != :usNo
			GROUP BY followTable.followingNo
			ORDER BY
				master.usNo= :usNo DESC,
				followTable.followNo ASC
		";
		// LIMIT :limit
		// OFFSET :offset

		// ':limit' => $limit,
		// ':offset' => $offset,

		return $this->fetchall($sql, array(
			':usNo' => $usNo,
			':lastCalcTime' => $lastCalcTime,
		));
	}


}
