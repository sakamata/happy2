<?php
class AdminRepository extends DbRepository
{
	public function fetchByAdminUserName($usId)
	{
		$sql = "SELECT * FROM adtbus WHERE usId = :usId";

		return $this->fetch($sql, array(':usId' => $usId));
	}

	public function fetchAlltbus($limit='10')
	{
		$sql = "
			SELECT * from tbus
			LIMIT :limit
		";

		return $this->fetchall($sql, array(':limit' => $limit));
	}

}
