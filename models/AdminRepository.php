<?php
class AdminRepository extends DbRepository
{
	public function fetchByAdminUserName($usId)
	{
		$sql = "SELECT * FROM adtbus WHERE usId = :usId";

		return $this->fetch($sql, array(':usId' => $usId));
	}

	// PassWordを非表示とするため別処理
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
		// ***危険*** 変数を直接クエリに入れている（mysqlの limitバグの為）
		// ***ToDo*** ここにPOSTされた値を拾った時点で型確認checkを書く
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

	public function tbusReset()
	{
		$sql="
			DROP TABLE tbus;
			CREATE TABLE IF NOT EXISTS `happy2`.`tbus` (
				`usNo` INT(11) NOT NULL AUTO_INCREMENT,
				`usId` VARCHAR(32) NULL,
				`usPs` VARCHAR(64) NULL,
				`usName` VARCHAR(32) NULL,
				`usImg` VARCHAR(256) NULL,
				`nowPt` DECIMAL(18,9) NULL,
				`ip` VARCHAR(100) NULL,
				`host` VARCHAR(100) NULL,
				`regDate` DATETIME NULL,
				PRIMARY KEY (`usNo`),
				UNIQUE INDEX `id_UNIQUE` (`usNo` ASC))
			ENGINE = InnoDB
		";

		$stmt = $this->execute($sql, array(
		));
	}

	public function tbgvnReset()
	{
		$sql="
			DROP TABLE tagvn;
			CREATE TABLE IF NOT EXISTS `happy2`.`tbgvn` (
				`gvnNo` INT(11) NOT NULL AUTO_INCREMENT,
				`usNo` INT(11) NULL,
				`seUs` INT(11) NULL,
				`seClk` INT(11) NULL,
				`dTm` DATETIME NULL COMMENT 'クリック数を毎回登録するためのTable',
				PRIMARY KEY (`gvnNo`))
			ENGINE = InnoDB
		";

		$stmt = $this->execute($sql, array(
		));
	}

	public function tbsetReset()
	{
		$sql="
			DROP TABLE tbset;
			CREATE TABLE IF NOT EXISTS `happy2`.`tbset` (
				`setNo` INT(11) NOT NULL AUTO_INCREMENT,
				`usNo` INT(11) NULL,
				`seUs` INT(11) NULL,
				`getPt` DECIMAL(18,9) NULL,
				`dTm` DATETIME NULL,
				PRIMARY KEY (`setNo`))
			ENGINE = InnoDB
		";

		$stmt = $this->execute($sql, array(
		));
	}

	public function tbcalctimeReset()
	{
		$sql="
		DROP TABLE tbcalctime;
		CREATE TABLE IF NOT EXISTS `happy2`.`tbcalctime` (
			`calcNo` INT(11) NOT NULL AUTO_INCREMENT,
			`calcTime` DATETIME NULL,
			PRIMARY KEY (`calcNo`))
		ENGINE = InnoDB
		";

		$stmt = $this->execute($sql, array(
		));
	}

	public function tbfollowReset()
	{
		$sql="
		DROP TABLE tbfollow;
		CREATE TABLE IF NOT EXISTS `happy2`.`tbfollow` (
			`usNo` INT(11) NULL,
			`followingNo` INT(11) NULL
		)
		ENGINE = InnoDB
		";

		$stmt = $this->execute($sql, array(
		));
	}

	public function tbusDummyIn()
	{
		//ToDo CSV 読み込み
		$sql="
		INSERT INTO tbus(usId, usName, usImg, nowPt, regDate) value('dummy3', 'taro', 'dummy.jpg', 100, now());
		";

		$stmt = $this->execute($sql, array(
		));
	}

	public function tbgvnDummyIn()
	{
		//ToDo CSV 読み込み
		$sql="
		INSERT INTO tbgvn(usNo,seUs,seClk,dTm) value(1,2,5,now());
		";

		$stmt = $this->execute($sql, array(
		));
	}

	public function tbsetDummyIn()
	{
		//ToDo CSV 読み込み
		$sql="
			INSERT INTO tbset(usNo,seUs,getPt,dTm) value(1,2,10,now());
		";

		$stmt = $this->execute($sql, array(
		));
	}

	public function tbfollowDummyIn()
	{
		$sql="
			INSERT INTO tbfollow(usNo,followingNo) value(3,4);
		";

		$stmt = $this->execute($sql, array(
		));
	}

	public function tbcalctimeDummyIn()
	{
		$sql="
			INSERT INTO tbcalctime(calcTime) value(now());
		";

		$stmt = $this->execute($sql, array(
		));
	}

	public function tableDelete($tableName)
	{
		$sql ="
			DROP TABLE $tableName
		";
		$stmt = $this->execute($sql, array(
			':tableName' => $tableName,
		));
	}

}
