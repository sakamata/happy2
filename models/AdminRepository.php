<?php
class AdminRepository extends DbRepository
{
	public function fetchByAdminUserName($usId)
	{
		$sql = "
			SELECT *
			FROM adtbus
			WHERE usId = :usId
		";

		return $this->fetch($sql, array(':usId' => $usId));
	}

	// PassWordを非表示とするため別処理
	public function fetchAlltbus($limit, $offset)
	{
		// ***危険*** 変数を直接クエリに入れている（mysqlの limitバグの為）
		// ***ToDo*** ここにPOSTされた値を拾った時点で型確認checkを書く
		$sql = "
			SELECT usNo, usId, usName, usImg, nowPt, ip, host, regDate
			FROM tbus
			ORDER BY usNo DESC
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
			SELECT *
			FROM $tableName
			ORDER BY 1 DESC
			LIMIT $limit
			OFFSET $offset
		";

		return $this->fetchAll_bindParam($sql, array(
			':tableName' => $tableName,
			':limit' => $limit,
			':offset' => $offset,
		));
	}

	public function allUsersPtsSum()
	{
		$sql = "
			SELECT sum(nowPt) AS nowPt
			FROM tbus
		";

		return $this->fetch($sql, array(
		));
	}

	public function getCalcTimeBetweenAndLastTime()
	{
		$sql = "
			SELECT calctime
			FROM tbcalctime
			ORDER BY calcNo DESC
			LIMIT 2
		";

		return $this->fetchall($sql, array(
		));
	}


	public function getSumPtsLastTime($lastTime, $lastButOne)
	{
		$sql = "
			SELECT sum(getPt) as userPts
			FROM tbset
			WHERE dTm between :lastButOne AND :lastTime
		";

		return $this->fetch($sql, array(
			':lastTime' => $lastTime,
			':lastButOne' => $lastButOne,
		));
	}

	public function tableCount($tableName, $reqColumn = null, $reqValue = null, $reqColumn2 = null, $reqValue2 = null)
	{
		$sql = "
			SELECT COUNT(*) AS $tableName
			FROM $tableName
		";

		if (isset($reqColumn) && isset($reqValue)) {
			$sql .= "
				WHERE $reqColumn = $reqValue
			";
		}

		if (isset($reqColumn2) && isset($reqValue2)) {
			$sql .= "
				AND $reqColumn2 = $reqValue2
			";
		}

		return $this->fetch($sql, array(
			':tableName' => $tableName,
			':reqColumn' => $reqColumn,
			':reqValue' => $reqValue,
			':reqColumn2' => $reqColumn2,
			':reqValue2' => $reqValue2,
		));
	}

	public function tbusReset()
	{
		$sql = "
			DROP TABLE tbus;
			CREATE TABLE IF NOT EXISTS `happy2`.`tbus` (
				`usNo` INT(11) NOT NULL AUTO_INCREMENT,
				`usId` VARCHAR(32) NOT NULL,
				`usPs` VARCHAR(64) NULL,
				`usName` VARCHAR(32) NULL,
				`usImg` VARCHAR(256) NULL,
				`nowPt` DECIMAL(18,9) NULL,
				`ip` VARCHAR(100) NULL,
				`host` VARCHAR(100) NULL,
				`regDate` DATETIME NULL,
				`facebookId` VARCHAR(64) NULL,
				PRIMARY KEY (`usNo`),
				UNIQUE INDEX `id_UNIQUE` (`usNo` ASC),
				UNIQUE INDEX `usId_UNIQUE` (`usId` ASC),
				UNIQUE INDEX `facebookId_UNIQUE` (`facebookId` ASC)
			)
			ENGINE = InnoDB
		";

		$stmt = $this->execute($sql, array(
		));
	}

	public function tbgvnReset()
	{
		$sql = "
			DROP TABLE tbgvn;
			CREATE TABLE IF NOT EXISTS `happy2`.`tbgvn` (
				`gvnNo` INT(11) NOT NULL AUTO_INCREMENT,
				`usNo` INT(11) NULL,
				`seUs` INT(11) NULL,
				`seClk` INT(11) NULL,
				`systemClk` TINYINT(1) NULL DEFAULT 0,
				`dTm` DATETIME NULL COMMENT 'クリック数を毎回登録するためのTable',
				PRIMARY KEY (`gvnNo`))
			ENGINE = InnoDB
		";

		$stmt = $this->execute($sql, array(
		));
	}

	public function tbsetReset()
	{
		$sql = "
			DROP TABLE tbset;
			CREATE TABLE IF NOT EXISTS `happy2`.`tbset` (
				`setNo` INT(11) NOT NULL AUTO_INCREMENT,
				`setGvnNo` INT NULL,
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
		$sql = "
			DROP TABLE tbcalctime;
			CREATE TABLE IF NOT EXISTS `happy2`.`tbcalctime` (
				`calcNo` INT(11) NOT NULL AUTO_INCREMENT,
				`calcTime` DATETIME NULL,
				PRIMARY KEY (`calcNo`))
			ENGINE = InnoDB;
			INSERT INTO tbcalctime(calcTime) VALUE(now());
		";

		$stmt = $this->execute($sql, array(
		));
	}

	public function tbfollowReset()
	{
		$sql = "
			DROP TABLE tbfollow;
			CREATE TABLE IF NOT EXISTS `happy2`.`tbfollow` (
				`followNo` INT NOT NULL AUTO_INCREMENT,
				`usNo` INT(11) NULL,
				`followingNo` INT(11) NULL,
				PRIMARY KEY (`followNo`))
			ENGINE = InnoDB
		";

		$stmt = $this->execute($sql, array(
		));
	}

	public function tb_user_statusReset()
	{
		$sql = "
			DROP TABLE tb_user_status;
			CREATE TABLE IF NOT EXISTS `happy2`.`tb_user_status` (
			  `No` INT NOT NULL AUTO_INCREMENT,
			  `usNo` INT NOT NULL,
			  `lastActiveTime` TIMESTAMP NULL,
			  `latitude` DECIMAL(14,7) NULL,
			  `longitude` DECIMAL(14,7) NULL,
			  `tb_user_statuscol` VARCHAR(45) NULL,
			  UNIQUE INDEX `usNo_UNIQUE` (`usNo` ASC),
			  PRIMARY KEY (`No`),
			  UNIQUE INDEX `No_UNIQUE` (`No` ASC))
			ENGINE = InnoDB
		";

		$stmt = $this->execute($sql, array(
		));
	}

	public function tbusDummysIn()
	{
		$sql = "
		LOAD DATA INFILE '../../htdocs/happy2/data/tbus_dummy02.csv'
		INTO TABLE tbus
		FIELDS TERMINATED BY ','
		IGNORE 1 LINES
		";

		$stmt = $this->execute($sql, array(
		));
	}

	public function tbgvnDummysIn($dummys)
	{
		$no3 = $dummys['no3'];
		$userCount = $dummys['userCount'];
		$sql = "
			INSERT INTO
				tbgvn(usNo,seUs,seClk,dTm)
				VALUES
		";
		$a = 0;
		while ($a < $userCount - 1) {
			$no3 = rand(1,$userCount);
			$no4 = rand(1,$userCount);
			$clk = rand(1,99);
			$sql .= "
				($no3, $no4, $clk, now()),
			";
			$a++;
		}
		$no3 = rand(1,$userCount);
		$no4 = rand(1,$userCount);
		$clk = rand(1,99);
		$sql .= "
			($no3, $no4, $clk, now());
		";

		$stmt = $this->execute($sql, array(
		));
	}

	public function tbsetDummysIn($dummys)
	{
		//ToDo CSV 読み込み
		$sql = "
			INSERT INTO
				tbset(usNo,seUs,getPt,dTm)
				VALUE(1,2,10,now());
		";

		$stmt = $this->execute($sql, array(
		));
	}

	public function tbfollowDummsyIn($dummys)
	{
		$sql = "
			INSERT INTO
				tbfollow(usNo,followingNo)
				VALUE(3,4);
		";

		$stmt = $this->execute($sql, array(
		));
	}

	public function tbusDummyIn($dummys)
	{
		$sql = "
			INSERT INTO
				tbus(usId, usName, usPs, usImg, nowPt, regDate)
			VALUE(
				:usId,
				:usName,
				'7599fcea685786ffa4f9314259a155301ed24f5d',
				'default/dummy.png',
				100,
				now()
			);
		";

		$stmt = $this->execute($sql, array(
			':usId' => $dummys['usId'],
			':usName' => $dummys['usName'],
		));
	}


	public function getDummyUserNo($userId)
	{
		$sql = "
			SELECT usNo, usId
			FROM tbus
			WHERE usId = :userId
		";

		return $this->fetch($sql, array(
			':userId' => $userId,
		));
	}

	public function tbusDummyIn_SelfOneClick($usNo)
	{
		$sql = "
			INSERT INTO tbgvn(usNo, seUs, seClk, dTm)
			VALUES($usNo, $usNo, 1, now())
		";

		$stmt = $this->execute($sql, array(
		));
	}

	public function tbgvnDummyIn($dummys)
	{
		$sql = "
		INSERT INTO
			tbgvn(usNo,seUs,seClk,dTm)
			VALUE(:no1, :no2, :clk, now());
		";

		$stmt = $this->execute($sql, array(
			':no1' => $dummys['no1'],
			':no2' => $dummys['no2'],
			':clk' => $dummys['clk'],
		));
	}

	public function tbsetDummyIn($dummys)
	{
		$sql = "
			INSERT INTO
				tbset(usNo,seUs,getPt,dTm)
				VALUE(:no1, :no2, 10, now());
		";

		$stmt = $this->execute($sql, array(
			':no1' => $dummys['no1'],
			':no2' => $dummys['no2']
		));
	}

	public function tbfollowDummyIn($dummys)
	{
		$sql = "
			INSERT INTO
				tbfollow(usNo,followingNo)
				VALUE(:no1, :no2);
		";

		$stmt = $this->execute($sql, array(
			':no1' => $dummys['no1'],
			':no2' => $dummys['no2']
		));
	}

	public function tbcalctimeDummyIn()
	{
		$sql = "
			INSERT INTO
				tbcalctime(calcTime)
				VALUE(now());
		";

		$stmt = $this->execute($sql, array(
		));
	}


	// ***** PtDefaultAction *****

	public function PtDefault_tbus($DefaultPt)
	{
		$sql = "
			UPDATE tbus
			SET nowPt = $DefaultPt
		";

		$stmt = $this->execute($sql, array(
		));
	}



	// ***** calc *****

	public function tbcalctimeInsertPlus1sec()
	{
		$sql = "
			INSERT INTO
				tbcalctime(calcTime)
				VALUE(now() + interval 1 second);
		";

		$stmt = $this->execute($sql, array(
		));
	}

	public function lastCalcTime()
	{
		$sql = "
			SELECT max(calcTime) AS date
			FROM tbcalctime
		";

		return $stmt = $this->fetch($sql, array(
		));
	}

	public function checkTodayClick($lastCalcTime)
	{
		$sql = "
			SELECT gvnNo, dTm
				FROM tbgvn
			WHERE
				systemClk = false
				AND
				dTm BETWEEN :lastCalcTime AND now()
		";

		return $stmt = $this->fetch($sql, array(
			':lastCalcTime' => $lastCalcTime,
		));
	}

	public function clkUsersClkSumAndPts($lastCalcTime, $now)
	{
		$sql = "
			SELECT gvn.gvnNo, gvn.usNo, gvn.clk_sum, user.nowPt
			FROM tbus
			AS user
			JOIN(
				SELECT gvnNo, usNo, sum(seClk) AS clk_sum
				FROM tbgvn
				WHERE tbgvn.dTm BETWEEN :lastCalcTime AND :now
				GROUP BY usNo
			)
			AS gvn
			ON user.usNo = gvn.usNo
			ORDER BY user.usNo DESC
		";

		return $stmt = $this->fetchall($sql, array(
			':lastCalcTime' => $lastCalcTime,
			':now' => $now,
		));
	}

	public function sendClksSumToUser($lastCalcTime, $now, $usNo)
	{
		$sql = "
			SELECT gvnNo, usNo, seUs, seClk, dTm
			FROM tbgvn
			WHERE
					usNo = :usNo
				AND
					dTm BETWEEN :lastCalcTime
				AND
					:now
				ORDER BY gvnNo ASC
		";

		return $this->fetchall($sql, array(
			':lastCalcTime' => $lastCalcTime,
			':now' => $now,
			':usNo' => $usNo,
		));
	}

	public function startTransaction()
	{
		$sql = "START TRANSACTION;";
		$stmt = $this->execute($sql, array());
	}

	public function TransactionCommit()
	{
		$sql = "COMMIT;";
		$stmt = $this->execute($sql, array());
	}

	public function clkUsersPts_tbsetInsert($setGvnNo, $usNo, $seUs, $getPt, $dTm)
	{
		$sql = "
			INSERT INTO
				tbset(setGvnNo, usNo, seUs, getPt, dTm)
				VALUE(:setGvnNo, :usNo, :seUs, :getPt, :dTm);
		";

		$stmt = $this->execute($sql, array(
			':setGvnNo' => $setGvnNo,
			':usNo' => $usNo,
			':seUs' => $seUs,
			':getPt' => $getPt,
			':dTm' => $dTm,
		));
	}

	public function sendUsersGetPtsSum($lastCalcTime, $now)
	{
		$sql = "
			SELECT seUs, sum(getPt) AS getPt
			FROM tbset
			WHERE dTm between :lastCalcTime and :now
			GROUP BY seUs
			ORDER BY seUs
		";

		return $this->fetchall($sql, array(
			':lastCalcTime' => $lastCalcTime,
			':now' => $now,
		));
	}

	public function clkUsersRivisePts_TogetherInsert($sendUsersNo, $rivisePts, $now)
	{
		$sql = "
			INSERT INTO
				tbset(usNo, seUs, getPt, dTm)
				VALUES
		";

		$b = 0;
		if (count($sendUsersNo) == 1 ) {
			$sql .= "
				('0', $sendUsersNo[$b], $rivisePts[$b], :now);
			";
		} else {
			while ($b < count($sendUsersNo) - 1) {
				$sql .= "
					('0', $sendUsersNo[$b], $rivisePts[$b], :now),
				";
				$b++;
			}

			$sql .= "
				('0', $sendUsersNo[$b], $rivisePts[$b], :now);
			";
		}

		$stmt = $this->execute($sql, array(
			':now' => $now,
		));
	}

	// 集計の最小Ptかつ最新ユーザー探す
	public function getMaxPtOldUser()
	{
		$sql = "
			SELECT usNo, nowPt
			FROM tbus
			WHERE nowPt = (
				SELECT MAX(nowPt)
				FROM tbus
				)
			ORDER BY usNo ASC
		";

		return $this->fetchall($sql, array(
		));
	}

	// 集計の最大Ptかつ古参ユーザーを探す
	public function getMinPtNewUser()
	{
		$sql = "
			SELECT usNo, nowPt
			FROM tbus
			WHERE nowPt = (
				SELECT MIN(nowPt)
				FROM tbus
				)
			ORDER BY usNo DESC
		";

		return $this->fetchall($sql, array(
		));
	}

	public function getAllToleranceUser($lastCalcTime, $now, $order)
	{
		$sql = "
			SELECT seUs, sum(getPt) AS sum
			FROM tbset
			WHERE dTm between :lastCalcTime AND :now
			GROUP BY seUs
			ORDER BY sum $order
			limit 1
		";

		return $this->fetchall($sql, array(
			':lastCalcTime' => $lastCalcTime,
			':now' => $now,
		));
	}

	public function ToleranceInsert($sendUsersNo, $rivisePts, $now)
	{
		$sql = "
			INSERT INTO
				tbset(usNo, seUs, getPt, dTm)
				VALUES('0', $sendUsersNo, $rivisePts, :now)
		";

		$stmt = $this->execute($sql, array(
			':now' => $now,
		));
	}

	public function getCalcResultPts($lastCalcTime, $now)
	{
		$sql = "
			SELECT seUs, sum(getPt) as userPts
			FROM tbset
			WHERE dTm between :lastCalcTime AND :now
			GROUP BY seUs
		";

		return $this->fetchall($sql, array(
			':lastCalcTime' => $lastCalcTime,
			':now' => $now,
		));
	}

	public function getCalcResultSumPts($lastCalcTime, $now)
	{
		$sql = "
			SELECT sum(getPt) as userPts
			FROM tbset
			WHERE dTm between :lastCalcTime AND :now
		";

		return $this->fetch($sql, array(
			':lastCalcTime' => $lastCalcTime,
			':now' => $now,
		));
	}

	public function calcResultPts_tbusInsert($nowPts, $userNo)
	{
		$sql = "
			UPDATE tbus SET nowPt = :nowPts
			WHERE usNo = :userNo
		";

		$stmt = $this->execute($sql, array(
			':nowPts' => $nowPts,
			':userNo' => $userNo,
		));
	}

	public function getAllUserNo()
	{
		$sql = "
			SELECT usNo FROM tbus
		";

		return $this->fetchall($sql, array(
		));
	}

		public function allUserSelfOneClick($usersNo, $startTime)
	{
		$sql = "
			INSERT INTO tbgvn(usNo, seUs, seClk, systemClk, dTm)
			VALUES
		";

		$usNo = "";
		$a = 0;
		while ($a < count($usersNo) - 1) {
			$usNo = intval($usersNo[$a]['usNo']);
			$sql .=  "($usNo, $usNo, 1, true, :startTime)" . ',';
			$a++;
		}
		$usNo = intval($usersNo[$a]['usNo']);
		$sql .= "($usNo, $usNo, 1, true, :startTime)";

		$stmt = $this->execute($sql, array(
			':startTime' => $startTime,
		));
	}


}
