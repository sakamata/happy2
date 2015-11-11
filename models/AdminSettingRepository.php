<?php
class AdminSettingRepository extends DbRepository
{
		// ***ToDo*** 値の変更を管理画面で可能とする
	const userDefaultPoint = 100;

	// 初期値設定
	// SQLにPOST
	// SQLから呼び出し、　construct時にprivateにセット

	public function fetchSettingValue()
	{
		$sql = "
			SELECT * from adsetting WHERE no = 1
		";

		return $this->fetch($sql,array(
		));
	}

	public function updateSettingValue($setting, $value)
	{
		$sql = "
			UPDATE adsetting SET :setting = :value WHERE no = 1;
		";

		$stmt = $this->execute($sql, array(
			':setting' => $setting,
			':value' => $value,
		));
	}
}
