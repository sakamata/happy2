<!DOCTYPE html>
<html>
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>


		<meta charset="utf-8">
		<title></title>
		<script type="text/javascript">
		navigator.geolocation.getCurrentPosition(
			function(position){
				console.log(position.coords.latitude);
				console.log(position.coords.longitude);
			}
		);
		</script>


	</head>
	<body>



	</body>
</html>



<?php


$queryArray = array(
	'seUs' => array('1','2','3'),
	'getPt' => array('1.11','2.22','3.33' )
);

var_dump(is_array($queryArray));

var_dump(intval($queryArray));

var_dump($queryArray);
$a = 'hello';
// $$a = 'world';
//
// echo "$a"."<br>";// → hello
// echo "${$a}"."<br>";// → world
// echo "$hello"."<br>";// → world
//
// $b = 'llo';
// echo "${'he' . $b}"."<br>";// → world

$a();

function hore($value)
{
	echo 'hogehoge<br>';
}

$ccc = 'ccc';
hore($ccc);

// echo (makeRandStr());

function hello()
{
	echo 'こんにちは！<br>';
}

$command = 'delete';
$table = 'tbus';

$ReposCmd = $command.'_'.$table;

echo $ReposCmd;

$fruits = array( "a", "b", "c", "d",
			// array( "AA","BB","CC")
);

var_dump($fruits);

foreach ($fruits as $key) {
	echo $key . '<br>';
	// echo $key[0];
}

$AllPtsTolerance = -0.1;
$errRange = 0.1;


echo '誤差処理<br>';
if (abs($AllPtsTolerance - 0) >= $errRange) {
	 echo '誤差処理をするtrue<br><br>';
}


// $smarty = new Smarty();
// $smarty->template_dir = 'C:/xampp/htdocs/happy2/views/templates/';
// $smarty->compile_dir  = 'C:/xampp/htdocs/happy2/views/templates_c/';
// $smarty->config_dir   = 'C:/xampp/htdocs/happy2/views/configs/';
// $smarty->cache_dir    = 'C:/xampp/htdocs/happy2/views/cache/';
//
// $smarty->assign('msg','Hello World!');

// $smarty->display('index2.php');



?>
SELECT usNo,sum(seClk) AS clkSum from tbgvn
		WHERE dTm between '2015-11-15 18:52:16' and now()
		GROUP by usNo


SELECT seUs,sum(seClk) AS sendClkSum from tbgvn
			WHERE dTm between '2015-11-15 18:52:16' and now()
				and usNo=2
				GROUP by seUs


SELECT seUs,sum(seClk) AS sendClkSum from tbgvn
			WHERE dTm between '2015-11-30 16:00:00' and now()
				GROUP by seUs

SELECT seUs,sum(seClk) AS sendClkSum from tbgvn
			WHERE dTm between '2015-11-15 18:52:16' and now()
				GROUP by seUs OR usNo

SELECT * from tbgvn
GROUP by usNo AND seUs


select usNo, seUs, sum(seClk)
from tbgvn
WHERE dTm between '2015-11-18 16:10:16' and now()
group by usNo, seUs
order by usNo, seUs

select usNo, seUs, sum(seClk)
from tbgvn
group by usNo, seUs
order by usNo, seUs


SELECT tbus.usNo, tbus.usId, tbus.usName, tbus.nowPt FROM tbus
	JOIN tbgvn
	ON tbus.usNo = tbgvn.usNo
	WHERE tbgvn.dTm between :lastCalcTime and now()
	GROUP BY tbus.usNo

	SELECT tbus.usNo, tbus.usId, tbus.usName, tbus.nowPt FROM tbus
		JOIN tbgvn
		ON tbus.usNo = tbgvn.usNo
		WHERE tbgvn.dTm between '2015-11-15 18:52:16' and now()
		GROUP BY tbus.usNo


		SELECT usNo, seUs, seClk, dTm
		FROM tbgvn
		WHERE
				usNo = 7
			AND
				dTm BETWEEN '2015-11-15 18:52:16'
			AND
				now()
		ORDER BY gvnNo

		ORDER BY usNo, seUs
		GROUP BY usNo, seUs

		SELECT tbus.usNo, tbus.usId, tbgvn.sum(seClk) AS clkSum, tbus.nowPt
		FROM tbus
		JOIN tbgvn
			ON tbus.usNo = tbgvn.usNo
		WHERE tbgvn.dTm BETWEEN :lastCalcTime AND now()
		GROUP BY tbus.usNo

SELECT
	gvn.usNo,
	gvn.seClk_sum,
	user.nowPt
FROM
	tbus
AS user

JOIN(
	SELECT usNo, sum(seClk) AS seClk_sum
	FROM tbgvn
	WHERE tbgvn.dTm BETWEEN '2015-11-15 18:52:16' AND now()
	GROUp BY usNo
)
AS gvn

ON user.usNo = gvn.usNo


SELECT seUs, sum(getPt) AS getPt
FROM tbset
WHERE dTm between '2015-11-26 16:29:32' and now()
GROUP BY seUs
ORDER BY seUs


INSERT into tbgvn(usNo,seUs,seClk,dTm)
VALUES(100, 1000, 1, '2015-11-30 16:00:00' + interval 2 second)

'2015-11-30 16:00:00'

SELECT seUs, sum(getPt) as userPts
FROM tbset
WHERE dTm between '2015-12-03 17:30:21' AND now()

SELECT usNo, nowPt
FROM tbus
WHERE nowPt = (
	SELECT MAX(nowPt)
	FROM tbus
)

SELECT usNo, nowPt
FROM tbus
WHERE nowPt = (
	SELECT MIN(nowPt)
	FROM tbus
)
	ORDER BY usNo DESC


SELECT seUs, sum(getPt)
FROM tbset
WHERE
 getPt = (
	SELECT MAX(getPt)
	FROM tbset
 )
GROUP BY seUs

 SELECT seUs, sum(getPt) AS sum
 FROM tbset
 WHERE dTm between '2015-12-05 01:11:24' AND now()
 GROUP BY seUs
 ORDER BY sum desc
limit 1

desc max
asc min

SELECT seUs, sum(getPt) as userPts
FROM tbset
WHERE dTm between '2015-12-07 23:56:58' AND now()
GROUP BY seUs

SELECT sum(getPt) as userPts
FROM tbset
WHERE dTm between 0 AND now()


select calcNo, calctime from tbcalctime order by calcNo desc limit 2 offset 1;


SELECT calcTime, COUNT(calcNo) AS countCalc FROM tbcalctime ORDER BY calcNo DESC

SELECT MAX(calcTime) AS lastCalcTime, COUNT(calcNo) AS countCalc FROM tbcalctime

SELECT master.usNo , master.usId , gvn.field FROM tbus AS master
LEFT JOIN(
	SELECT usNo, ifnull(sum(seClk), '0') AS field from tbgvn
	WHERE dTm between '2015-12-16 13:50:39' and now()
	GROUP BY usNo
) AS gvn
ON master.usNo=gvn.usNo
ORDER BY master.usId='taro' desc , master.usNo asc


フォローしているユーザーの一覧の基本情報を表示

SELECT
	usNo,
	usId,
	usName,
	usImg,
	nowPt
FROM tbus
WHERE usNo

IN(
	SELECT
		followingNo
	FROM tbfollow
	WHERE usNo = 10
	ORDER BY usNo ASC
)


フォローされているユーザーの一覧の基本情報を表示

SELECT
	usNo,
	usId,
	usName,
	usImg,
	nowPt
FROM tbus
WHERE usNo

IN(
	SELECT
		usNo
	FROM tbfollow
	WHERE followingNo = 8
	ORDER BY followingNo DESC
)



フォローしているユーザーの一覧の基本情報を表示
+ クリック総数を表示


SELECT
	master.usNo,
	master.usId,
	master.usName,
	master.usImg,
	master.nowPt,
	gvnTable.clkSum
	FROM tbus
	AS master

LEFT JOIN(
	SELECT
		usNo,
		sum(seClk) AS clkSum
		FROM tbgvn
		GROUP BY usNo
)
AS gvnTable
ON master.usNo = gvnTable.usNo

WHERE
	master.usNo
	IN(
		SELECT
			followingNo
			FROM tbfollow
			WHERE usNo = 10
			ORDER BY usNo ASC
	)






各ユーザーがseUs(自分)へクリックした数を表示

SELECT usNo, SUM(seClk) AS clkSum
FROM tbgvn
WHERE seUs =2
	AND dTm between '2015-12-16 13:50:39'
	AND now()
GROUP BY usNo
ORDER BY usNo ASC

------------------------------------------
倉林さんの参考クエリ（エラーになる）
	select
	master.usNo,
	master.usId,
	master.nowPt,
	gvnTable.seUs,
	sum(gvnTable.seClk) clkSum
	from tbus as master
	left outer join tbgvn as gvnTable
	on master.usNo = gvnTable.usNo

	left outer join tbfollow as followTable
	on master.usNo = followTable.usNo
	,tbfollow
	Where
	master.usNo in followTable.followingNo
	and
	followTable.usNo = 10
	group by
	master.usNo,
	master.usId,
	master.nowPt,
	gvnTable.seUs
	order by
	master.usNo

-------------------------------------------------
フォローしているユーザーの一覧の基本情報を表示
自分は除外（ページ送りをする為）
各ユーザーのクリック総数を表示
各ユーザーがseUs(自分)へクリックした数を表示


	SELECT
		master.usNo,
		master.usId,
		master.usName,
		master.usImg,
		master.nowPt,
		gvnTable.allClkSum,
		IFNULL(gvnTable2.toMeClkSum, 0) AS toMeClkSum
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
						WHERE usNo = 10
				)
			GROUP BY usNo
	)
	AS gvnTable
	ON master.usNo = gvnTable.usNo

	LEFT JOIN(
		SELECT usNo, SUM(seClk) AS toMeClkSum
			FROM tbgvn
			WHERE seUs = 10
				-- AND dTm between '2015-12-16 13:50:39'
				-- AND now()
				GROUP BY usNo
	)
	AS gvnTable2
	ON master.usNo = gvnTable2.usNo

	LEFT JOIN(
		SELECT followNo, usNo, followingNo
			FROM tbfollow
			WHERE usNo = 10
	)
	AS followTable
	ON master.usNo = followTable.followingNo

	WHERE
		master.usNo
		IN(
			SELECT
				followingNo
				FROM tbfollow
				WHERE usNo = 10
		)
	AND master.usNo != 10
	GROUP BY followTable.followingNo
	ORDER BY
		master.usNo=10 DESC,
		followTable.followNo ASC
	LIMIT 5
	OFFSET 2

------------------------------------------------------
フォロー中のユーザーのレコード数を求める

	SELECT count(followingNo) AS UserCount
		FROM tbfollow
		WHERE usNo = 10


-----------------------------------------------------
ユーザー1名の基本情報を表示 自分クリック数合計、Nユーザーへの今回のクリック数
自分ページ、他人ページで兼用できるように


thisUserAllClkSum
thisUserToMeClkSum


SELECT
	master.usNo,
	master.usId,
	master.usName,
	master.usImg,
	master.nowPt,
	IFNULL(gvnTable.myAllClkSum, 0) AS myAllClkSum,
	IFNULL(gvnTable2.thisUserSendClkSum, 0) AS thisUserSendClkSum,
	IFNULL(gvnTable3.thisUserAllClkSum, 0) AS thisUserAllClkSum,
	IFNULL(gvnTable4.thisUserToMeClkSum, 0) AS thisUserToMeClkSum
	FROM tbus
	AS master

LEFT JOIN(
	SELECT
		usNo,
		SUM(seClk) AS myAllClkSum
		FROM tbgvn
		WHERE
			usNo = 10
			-- AND dTm between '2015-12-16 13:50:39'
			-- AND now()
)
AS gvnTable
ON master.usNo = gvnTable.usNo

LEFT JOIN(
	SELECT usNo,
		SUM(seClk) AS thisUserSendClkSum
		FROM tbgvn
		WHERE
			usNo = 10
		AND
			seUs = 1
			-- ↑（他の値と異なるケース有）ログインユーザー自身となる
			-- AND dTm between '2015-12-16 13:50:39'
			-- AND now()
)
AS gvnTable2
ON master.usNo = gvnTable2.usNo

LEFT JOIN(
	SELECT usNo,
		SUM(seClk) AS thisUserAllClkSum
		FROM tbgvn
		WHERE
			usNo = 1
			-- AND dTm between '2015-12-16 13:50:39'
			-- AND now()
)
AS gvnTable3
ON master.usNo = gvnTable3.usNo

LEFT JOIN(
	SELECT usNo,
		SUM(seClk) AS thisUserToMeClkSum
		FROM tbgvn
		WHERE
			usNo = 1
		AND
			seUs = 10
			-- ↑対象ユーザー
			-- AND dTm between '2015-12-16 13:50:39'
			-- AND now()
)
AS gvnTable4
ON master.usNo = gvnTable4.usNo

WHERE
	master.usNo = 10


-------------------------------------------------

新規ユーザーの一覧を表示


SELECT
	master.usNo,
	master.usId,
	master.usName,
	master.usImg,
	master.nowPt,
	IFNULL(gvnTable.allClkSum, 0) AS allClkSum,
	IFNULL(gvnTable2.toMeClkSum, 0) AS toMeClkSum,
	IF(ifFollowing.followingNo > 0, 1, 0) AS ifFollowing,
	IF(ifFollower.usNo > 0, 1, 0) AS ifFollower
	FROM tbus
	AS master

LEFT JOIN(
	SELECT
		usNo,
		sum(seClk) AS allClkSum
		FROM tbgvn
		WHERE
			dTm between '2015-12-20 02:00:00'
			AND now()
		GROUP BY usNo
)
AS gvnTable
ON master.usNo = gvnTable.usNo

LEFT JOIN(
	SELECT usNo, SUM(seClk) AS toMeClkSum, dTm
		FROM tbgvn
		WHERE seUs = 10
			AND dTm between '2015-12-20 02:00:00'
			AND now()
			GROUP BY usNo
)
AS gvnTable2
ON master.usNo = gvnTable2.usNo

-- フォローしているか
LEFT JOIN(
	SELECT usNo, followingNo
		FROM tbfollow
		WHERE usNo = 10
)
AS ifFollowing
ON master.usNo = ifFollowing.followingNo

-- フォローされているか
LEFT JOIN(
	SELECT usNo, followingNo
		FROM tbfollow
		WHERE followingNo = 10
)
AS ifFollower
ON master.usNo = ifFollower.usNo

WHERE
	master.usNo != 10

ORDER BY master.usNo ASC
LIMIT 10
OFFSET 0


----------------------------------
新規ユーザー一覧に自分のクリック数を追加

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
			dTm between '2015-12-20 02:00:00'
			AND now()
		GROUP BY usNo
)
AS gvnTable
ON master.usNo = gvnTable.usNo

LEFT JOIN(
	SELECT usNo, SUM(seClk) AS toMeClkSum, dTm
		FROM tbgvn
		WHERE seUs = 10
			AND dTm between '2015-12-20 02:00:00'
			AND now()
			GROUP BY usNo
)
AS gvnTable2
ON master.usNo = gvnTable2.usNo

-- 自分のクリック数
LEFT JOIN(
	SELECT usNo, seUs,SUM(seClk) AS MySendClkSum
		FROM tbgvn
		WHERE usNo = 10
			AND dTm between '2015-12-20 02:00:00'
			AND now()
			GROUP BY seUs
)
AS gvnTable3
ON master.usNo = gvnTable3.seUs

WHERE
	master.usNo != 10
ORDER BY master.usNo ASC
LIMIT 10
OFFSET 0


------------------------------------------------


SELECT
	master.usNo,
	master.usId,
	-- master.usName,
	-- master.usImg,
	-- master.nowPt,
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
					WHERE followingNo = 10
			)
		GROUP BY usNo
)
AS gvnTable
ON master.usNo = gvnTable.usNo
LEFT JOIN(
	SELECT usNo, SUM(seClk) AS toMeClkSum
		FROM tbgvn
		WHERE seUs = 10
			AND dTm between '2015-12-20 02:00:00'
			AND now()
			GROUP BY usNo
)
AS gvnTable2
ON master.usNo = gvnTable2.usNo
LEFT JOIN(
	SELECT followNo, usNo, followingNo
		FROM tbfollow
		WHERE followingNo = 10
)
AS followTable
ON master.usNo = followTable.usNo

-- 自分のクリック数
LEFT JOIN(
	SELECT usNo, seUs,SUM(seClk) AS MySendClkSum
		FROM tbgvn
		WHERE usNo = 10
			AND dTm between '2015-12-20 02:00:00'
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
			WHERE followingNo = 10
	)
AND master.usNo != 10
GROUP BY followTable.usNo
ORDER BY
	followTable.followNo DESC
LIMIT 10
OFFSET 0
