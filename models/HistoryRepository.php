<?php

class HistoryRepository extends DbRepository
{
	public function fetchAllUsersHistry($limit, $offset, $order, $viewUser = null, $usersArray = null)
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
			# ToDo ここに絞り込み条件書く、負荷対策
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
		";

		if (isset($viewUser) && $usersArray === 'toSendHistory') {
			$sql .= "
				WHERE gvnMaster.usNo = :viewUser
			";
		}
		if (isset($viewUser) && $usersArray === 'receiveFromHistory') {
			$sql .= "
				WHERE gvnMaster.seUs = :viewUser
			";
		}

		$sql .= "
			ORDER BY
				gvnMaster.gvnNo $order
			LIMIT $limit
			OFFSET $offset
		";

		return $this->fetchAll($sql, array(
			':viewUser' => $viewUser,
		));
	}


}
