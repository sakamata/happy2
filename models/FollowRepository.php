<?php

class FollowRepository extends DbRepository
{
	public function following($usNo, $followingNo)
	{
		$sql = "INSERT INTO tbfollow(usNo, followingNo) VALUES(:usNo, :followingNo)";

		$stmt = $this->execute($sql, array(
			':usNo' => $usNo,
			':followingNo' => $followingNo,
		));
	}

	public function CheckFollowing($usNo, $followingNo)
	{
		$sql = "
			SELECT COUNT(usNo) as count
				FROM tbfollow
				WHERE usNo = :usNo
					AND followingNo = :followingNo
		";

		$row = $this->fetch($sql, array(
				':usNo' => $usNo,
				':followingNo' => $followingNo,
			));

		if ($row['count'] !== '0') {
			return true;
		}
		return false;
	}

	public function unFollow($usNo, $followingNo)
	{
		$sql = "
			DELETE FROM tbfollow
				WHERE usNo = :usNo
					AND followingNo = :followingNo
		";

		$stmt = $this->execute($sql, array(
			':usNo' => $usNo,
			':followingNo' => $followingNo,
		));
	}

}
