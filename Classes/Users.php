<?php

class Users {
    public static function addNew($data = array())
    {
        $sql = "INSERT users SET email = ?, password = ?, name = ?,
                contacts = ?, avatar_img = ?";
        $unitDataSql = [];
        foreach ($data as $value) {
            $unitDataSql[] = $value;
        }
        $user_id = DB::dataInsertion($sql,  $unitDataSql);
        if ($user_id) {
            return true;
        } else {
            return false;
        }
    }
}
