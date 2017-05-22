<?php

class DB {
	protected static $link = null;


	public static function getConnection() {
		if (self::$link) {
			return $link;
		} else {
			self::$link = mysqli_connect("localhost", "root", "mysql", "yeticave_db");
			return self::$link;
		}
   	}


   	public static function lastError() {
   		if (self::$link == null) {
   			return mysqli_connect_error();
   		} else {
   			return mysqli_error(self::$link);
   		}
   	}

   	   	
   	public static function dataRetrievalAssoc($sql, $unitDataSql, $oneRow = false )
	{
	    $resultArray = [];

	    $sqlReady = db_get_prepare_stmt(self::$link, $sql, $unitDataSql);

	    if (!$sqlReady) {
	        return $resultArray;
	    }

	    if (mysqli_stmt_execute($sqlReady)) {
	        $result = mysqli_stmt_get_result($sqlReady);
	    } else {
	        $result = false;
	    }

	    if ($result) {
	        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	            $resultArray[] = $row;
	        }
	    }

	    mysqli_stmt_close($sqlReady);

	    if ($oneRow && count($resultArray) == 1) {
	        return $resultArray[0];
	    } else {
	        return $resultArray;
	    }
	}

	public static function dataInsertion($sql, $unitDataSql)
	{
	    $sqlReady = db_get_prepare_stmt(self::$link, $sql, $unitDataSql);
	    if (!$sqlReady) {
	        return false;
	    }

	    if (mysqli_stmt_execute($sqlReady)) {
	        $result = mysqli_stmt_insert_id($sqlReady);
	    } else {
	        $result = false;
	    }
	    mysqli_stmt_close($sqlReady);
	    return $result;

	}

	//Функция для обновления данных, которая возвращает количество обновлённых записей.

	public static function dataUpdate($nameTable, $unitUpdatedData, $unitDataConditions)
	{
	    $updatingFields = "";
	    $updatingValues = [];

	    foreach ($unitUpdatedData as $key => $value) {
	        $updatingFields .= "`$key`=?, ";
	        $updatingValues[] = $value;
	    }

	    $updatingFields = substr($updatingFields, 0, -2);

	    $whereField = array_keys($unitDataConditions)[0];
	    $updatingValues[] = array_values($unitDataConditions)[0];

	    $sql = "UPDATE `$nameTable` SET $updatingFields WHERE `$whereField`=?;";

	    $sqlReady = db_get_prepare_stmt(self::$link, $sql, $updatingValues);

	    if (!$sqlReady) {
	        return false;
	    }
	    if (mysqli_stmt_execute($sqlReady)) {
	        $result = mysqli_stmt_affected_rows($sqlReady);

	    } else {
	        $result = false;
	    }
	    mysqli_stmt_close($sqlReady);
	    return $result;

	}




}
