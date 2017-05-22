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
}

