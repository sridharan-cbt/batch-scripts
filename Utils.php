<?php

class Utils {
    public static function dump_records($_fp, $_rows) {
        foreach ($_rows as $fields) {
            fputcsv($_fp, $fields, ",", "\"", "\\", "\n");
        }
    }

    public static function dump_group_records($_fp, $_rows) {
        foreach ($_rows as $key => $val ) {
            foreach($val as $fields) {
                fputcsv($_fp, $fields, ",", "\"", "\\", "\n");
            }
        }
    }

    public static function random_number_generator() {
        return bin2hex(random_bytes(5));
    }
}