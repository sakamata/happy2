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

	public function fetchHeaderUserPerson($viewUser, $usNo, $lastCalcTime)
	{
		$sql = "
			SELECT
				master.usNo,
				master.usId,
				master.usName,
				master.usImg,
				master.nowPt,
				IFNULL(gvnTable.thisUserAllClkSum, 0) AS thisUserAllClkSum,
				IFNULL(gvnTable2.thisUserToMeClkSum, 0) AS thisUserToMeClkSum

				FROM tbus
				AS master
			LEFT JOIN(
				SELECT
					usNo,
					SUM(seClk) AS thisUserAllClkSum
					FROM tbgvn
					WHERE
						usNo = :viewUser
					AND dTm between :lastCalcTime
					AND now()
			)
			AS gvnTable
			ON master.usNo = gvnTable.usNo

			LEFT JOIN(
				SELECT usNo,
					SUM(seClk) AS thisUserToMeClkSum
					FROM tbgvn
					WHERE
						usNo = :viewUser
					AND
						seUs = :usNo
					AND dTm between :lastCalcTime
					AND now()
			)
			AS gvnTable2
			ON master.usNo = gvnTable2.usNo

			WHERE
				master.usNo = :viewUser
		";

		return $this->fetch($sql, array(
			':usNo' => $usNo,
			':viewUser' => $viewUser,
			':lastCalcTime' => $lastCalcTime,
		));
	}

	public function CountFollowingDesc($usNo)
	{
		$sql = "
			SELECT count(followingNo) AS userCount
				FROM tbfollow
				WHERE usNo = :usNo
		";

		return $this->fetch($sql, array(
			':usNo' => $usNo,
		));

	}

	public function UsersArrayNewUser($usNo, $lastCalcTime, $limit, $offset, $order = 'desc')
	{
		$sql= "
			SELECT
				master.usNo,
				master.usId,
				master.usName,
				master.usImg,
				master.nowPt,
				IFNULL(gvnTable.allClkSum, 0) AS allClkSum,
				IFNULL(gvnTable2.toMeClkSum, 0) AS toMeClkSum
				FROM tbus
				AS master

			LEFT JOIN(
				SELECT
					usNo,
					sum(seClk) AS allClkSum
					FROM tbgvn
					WHERE
						dTm between :lastCalcTime
						AND now()
					GROUP BY usNo
			)
			AS gvnTable
			ON master.usNo = gvnTable.usNo

			LEFT JOIN(
				SELECT usNo, SUM(seClk) AS toMeClkSum, dTm
					FROM tbgvn
					WHERE seUs = :usNo
						AND dTm between :lastCalcTime
						AND now()
						GROUP BY usNo
			)
			AS gvnTable2
			ON master.usNo = gvnTable2.usNo

			WHERE
				master.usNo != :usNo
			ORDER BY master.usNo $order
			LIMIT $limit
			OFFSET $offset
		";

		return $this->fetchall($sql, array(
			':usNo' => $usNo,
			':lastCalcTime' => $lastCalcTime,

		));

	}

	public function testUsersArrayFollowUsers($usNo, $lastCalcTime)
	// $limit, $offset
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
