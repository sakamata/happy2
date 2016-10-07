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

	public function fetchClickStatus($usNo, $lastCalcTime)
	{
		$sql = "
			SELECT sum(seClk) AS allUsersSendClkSum
			FROM tbgvn
			WHERE
				usNo = :usNo
			AND
				dTm between :lastCalcTime
			AND now()
		";

		return $this->fetch($sql, array(
			':usNo' => $usNo,
			':lastCalcTime' => $lastCalcTime,
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
				IFNULL(gvnTable.thisTimeAllClkSum, 0) AS thisTimeAllClkSum,
				IFNULL(gvnTable2.thisTimeToMeClkSum, 0) AS thisTimeToMeClkSum,
				IFNULL(gvnTable3.MySendClkSum, 0) AS MySendClkSum,
				IF(ifFollowing.followingNo > 0, 1, 0) AS ifFollowing,
				IF(ifFollower.usNo > 0, 1, 0) AS ifFollower

				FROM tbus
				AS master
			LEFT JOIN(
				SELECT
					usNo,
					SUM(seClk) AS thisTimeAllClkSum
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
					SUM(seClk) AS thisTimeToMeClkSum
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

			LEFT JOIN(
				SELECT usNo, followingNo
					FROM tbfollow
					WHERE usNo = :viewUser
			)
			AS ifFollowing
			ON master.usNo = ifFollowing.followingNo

			LEFT JOIN(
				SELECT usNo, followingNo
					FROM tbfollow
					WHERE followingNo = :viewUser
			)
			AS ifFollower
			ON master.usNo = ifFollower.usNo

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
			SELECT count(followingNo) AS tableCount
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
			SELECT count(usNo) AS tableCount
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
				IFNULL(gvnTable.thisTimeAllClkSum, 0) AS thisTimeAllClkSum,
				IFNULL(gvnTable2.thisTimeToMeClkSum, 0) AS thisTimeToMeClkSum,
				IFNULL(gvnTable3.MySendClkSum, 0) AS MySendClkSum,
				IF(ifFollowing.followingNo > 0, 1, 0) AS ifFollowing,
				IF(ifFollower.usNo > 0, 1, 0) AS ifFollower
			FROM tbus
			AS master

			LEFT JOIN(
				SELECT
					usNo,
					sum(seClk) AS thisTimeAllClkSum
					FROM tbgvn
					WHERE
						dTm between :lastCalcTime
						AND now()
					GROUP BY usNo
			)
			AS gvnTable
			ON master.usNo = gvnTable.usNo

			LEFT JOIN(
				SELECT usNo, SUM(seClk) AS thisTimeToMeClkSum, dTm
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

			LEFT JOIN(
				SELECT usNo, followingNo
					FROM tbfollow
					WHERE usNo = :usNo
			)
			AS ifFollowing
			ON master.usNo = ifFollowing.followingNo

			LEFT JOIN(
				SELECT usNo, followingNo
					FROM tbfollow
					WHERE followingNo = :usNo
			)
			AS ifFollower
			ON master.usNo = ifFollower.usNo

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
				gvnTable.thisTimeAllClkSum,
				IFNULL(gvnTable2.thisTimeToMeClkSum, 0) AS thisTimeToMeClkSum,
				IFNULL(gvnTable3.MySendClkSum, 0) AS MySendClkSum,
				IF(ifFollowing.followingNo > 0, 1, 0) AS ifFollowing,
				IF(ifFollower.usNo > 0, 1, 0) AS ifFollower
			FROM tbus
				AS master
			LEFT JOIN(
				SELECT
					usNo,
					sum(seClk) AS thisTimeAllClkSum
					FROM tbgvn
					WHERE
						tbgvn.usNo
						IN(
							SELECT
								followingNo
								FROM tbfollow
								WHERE usNo = :usNo
						)
					AND tbgvn.dTm between :lastCalcTime
					AND now()
					GROUP BY usNo
			)
			AS gvnTable
			ON master.usNo = gvnTable.usNo
			LEFT JOIN(
				SELECT usNo, SUM(seClk) AS thisTimeToMeClkSum
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

			LEFT JOIN(
				SELECT usNo, followingNo
					FROM tbfollow
					WHERE usNo = :usNo
			)
			AS ifFollowing
			ON master.usNo = ifFollowing.followingNo

			LEFT JOIN(
				SELECT usNo, followingNo
					FROM tbfollow
					WHERE followingNo = :usNo
			)
			AS ifFollower
			ON master.usNo = ifFollower.usNo

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
				gvnTable.thisTimeAllClkSum,
				IFNULL(gvnTable2.thisTimeToMeClkSum, 0) AS thisTimeToMeClkSum,
				IFNULL(gvnTable3.MySendClkSum, 0) AS MySendClkSum,
				IF(ifFollowing.followingNo > 0, 1, 0) AS ifFollowing,
				IF(ifFollower.usNo > 0, 1, 0) AS ifFollower
			FROM tbus
				AS master
			LEFT JOIN(
				SELECT
					usNo,
					sum(seClk) AS thisTimeAllClkSum
					FROM tbgvn
					WHERE
						tbgvn.usNo
						IN(
							SELECT
								usNo
								FROM tbfollow
								WHERE followingNo = :usNo
						)
					AND tbgvn.dTm between :lastCalcTime
					AND now()
					GROUP BY usNo
			)
			AS gvnTable
			ON master.usNo = gvnTable.usNo
			LEFT JOIN(
				SELECT usNo, SUM(seClk) AS thisTimeToMeClkSum
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

			LEFT JOIN(
				SELECT usNo, followingNo
					FROM tbfollow
					WHERE usNo = :usNo
			)
			AS ifFollowing
			ON master.usNo = ifFollowing.followingNo

			LEFT JOIN(
				SELECT usNo, followingNo
					FROM tbfollow
					WHERE followingNo = :usNo
			)
			AS ifFollower
			ON master.usNo = ifFollower.usNo

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
