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

	public function countFollowing($usNo)
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

	public function countFollowers($usNo)
	{
		$sql = "
			SELECT count(usNo) AS userCount
				FROM tbfollow
				WHERE followingNo = :usNo
		";

		return $this->fetch($sql, array(
			':usNo' => $usNo,
		));
	}

	public function usersArrayNewUsers($usNo, $lastCalcTime, $limit, $offset, $order = 'DESC')
	{
		$sql= "
			SELECT
				master.usNo,
				master.usId,
				master.usName,
				master.usImg,
				master.nowPt,
				IFNULL(gvnTable.allClkSum, 0) AS allClkSum,
				IFNULL(gvnTable2.toMeClkSum, 0) AS toMeClkSum,
				IFNULL(gvnTable3.MySendClkSum, 0) AS MySendClkSum
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

			LEFT JOIN(
				SELECT usNo, seUs,SUM(seClk) AS MySendClkSum
					FROM tbgvn
					WHERE usNo = :usNo
						AND dTm between :lastCalcTime
						AND now()
					GROUP BY seUs
			)
			AS gvnTable3
			ON master.usNo = gvnTable3.seUs

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

	public function usersArrayFollowingUsers($usNo, $lastCalcTime, $limit, $offset, $order)
	{
		$sql = "
			SELECT
				master.usNo,
				master.usId,
				master.usName,
				master.usImg,
				master.nowPt,
				gvnTable.allClkSum,
				IFNULL(gvnTable2.toMeClkSum, 0) AS toMeClkSum,
				IFNULL(gvnTable3.MySendClkSum, 0) AS MySendClkSum
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

			-- 自分のクリック数
			LEFT JOIN(
				SELECT usNo, seUs,SUM(seClk) AS MySendClkSum
					FROM tbgvn
					WHERE usNo = :usNo
						AND dTm between :lastCalcTime
						AND now()
						GROUP BY seUs
			)
			AS gvnTable3
			ON master.usNo = gvnTable3.seUs

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
				followTable.followNo $order
			LIMIT $limit
			OFFSET $offset
		";

		return $this->fetchall($sql, array(
			':usNo' => $usNo,
			':lastCalcTime' => $lastCalcTime,
		));
	}

	public function usersArrayFollowersUsers($usNo, $lastCalcTime, $limit, $offset, $order)
	{
		$sql = "
			SELECT
				master.usNo,
				master.usId,
				master.usName,
				master.usImg,
				master.nowPt,
				gvnTable.allClkSum,
				IFNULL(gvnTable2.toMeClkSum, 0) AS toMeClkSum,
				IFNULL(gvnTable3.MySendClkSum, 0) AS MySendClkSum
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
								usNo
								FROM tbfollow
								WHERE followingNo = :usNo
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
					WHERE followingNo = :usNo
			)
			AS followTable
			ON master.usNo = followTable.usNo

			LEFT JOIN(
				SELECT usNo, seUs,SUM(seClk) AS MySendClkSum
					FROM tbgvn
					WHERE usNo = :usNo
						AND dTm between :lastCalcTime
						AND now()
						GROUP BY seUs
			)
			AS gvnTable3
			ON master.usNo = gvnTable3.seUs

			WHERE
				master.usNo
				IN(
					SELECT
						usNo
						FROM tbfollow
						WHERE followingNo = :usNo
				)
			AND master.usNo != :usNo
			GROUP BY followTable.usNo
			ORDER BY
				followTable.followNo $order
			LIMIT $limit
			OFFSET $offset
		";

		return $this->fetchall($sql, array(
			':usNo' => $usNo,
			':lastCalcTime' => $lastCalcTime,
		));
	}

}
