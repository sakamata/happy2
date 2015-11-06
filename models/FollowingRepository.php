<?php

class FollowingRepository extends DbRepository
{
	public function insert($usId, $following_id)
	{
		$sql = "INSERT INTO following VALUES(:usId, :following_id)";

		$stmt = $this->execute($sql, array(
			':usId' => $usId,
			':following_id' => $following_id,
		));
	}

	public function isFollowing($usId, $following_id)
	{
		$sql = "
			SELECT COUNT(usId) as count
				FROM following
				WHERE usId = :usId
					AND following_id = :following_id
		";

		$row = $this->fetch($sql, array(
				':usId' => $usId,
				':following_id' => $following_id,
			));

		if ($row['count'] !== '0') {
			return true;
		}

		return false;
	}

}
