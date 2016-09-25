<?php

class HistoryRepository extends DbRepository
{
	public function fetchAllUsersHistry($limit, $offset, $order)
	{
		$sql = "
			SELECT
				gvnMaster.gvnNo,
				gvnMaster.usNo AS fromNo,
				IFNULL(setPtTable.setGvnNo,'-') AS setGvnNo,
				userTable.usId AS fromId,
				userTable.usName AS fromName,
				userTable.usImg AS fromImg,
				gvnMaster.seClk AS formClickCount,
				gvnMaster.seUs AS toUserNo,
				toUserValue.usId AS toUserId,
				toUserValue.usName AS toUserName,
				toUserValue.usImg AS toUserImg,
				IFNULL(setPtTable.getPt, 'undecided') AS getPt,
				gvnMaster.dTm
			FROM tbgvn
				AS gvnMaster

			LEFT JOIN(
				SELECT usNo, usId, usName, usImg, nowPt
				FROM tbus
			)
				AS userTable
			ON gvnMaster.usNo = userTable.usNo

			LEFT JOIN(
				SELECT setGvnNo, usNo, seUs, getPt
				FROM tbset
				ORDER BY
					setGvnNo $order
			# 下記はNG rowの並びが異なる為、ToDo 絞り込みしないと重くなる
			#	LIMIT $limit
			#	OFFSET $offset
			)
				AS setPtTable
			ON gvnMaster.gvnNo = setPtTable.setGvnNo

			LEFT JOIN(
				SELECT
					usNo, usId, usName, usImg
				FROM tbus
			)
				AS toUserValue
			ON gvnMaster.seUs = toUserValue.usNo

			#WHERE
			#	gvnMaster.dTm BETWEEN '2016-08-22 12:06:30'
			#	AND now()

			ORDER BY
				gvnMaster.gvnNo $order
			LIMIT $limit
			OFFSET $offset
		";

		return $this->fetchAll_bindParam($sql, array(
			':offset' => $offset,
			':limit' => $limit,
		));
	}


}
