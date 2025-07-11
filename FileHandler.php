<?php

require_once 'Utils.php';

class FileHandler {
    public $rows;
    public $fp;

    public function __construct() {
        $this->rows = [];
    }

    public function set_data_rows($_rows) {
        $this->rows = $_rows;
    }

    public function write_a_csv_file(string $_filename, string $_mode, callable $_utils) {
        $this->fp = fopen($_filename, $_mode);
        $_utils($this->fp, $this->rows);
        
        fclose($this->fp);
    }

    public function dump_records($_filename, $_mode = 'a') {
        $this->write_a_csv_file($_filename, $_mode, "Utils::dump_records");
    }

    public function find_duplicate_records($_rfilename, $_wfilename, $_mode = "r", $flag = 0) {
        $this->fp = fopen($_rfilename, $_mode);

        $non_duplicate_records = [];
        $duplicate_records = [];

        $duplicate_ids = [];
        $non_duplicate_ids = [];
        while(($data = fgetcsv($this->fp, 1000, ",", "\"", "\\")) !== false) {
            if (in_array($data[0], $non_duplicate_ids)) {
                $duplicate_ids[] = $data[0];
                $duplicate_records[] = $data;
                continue;
            }

            $non_duplicate_ids[] = $data[0];
            $non_duplicate_records[] = $data;
        }
        fclose($this->fp);

        $this->set_data_rows($flag ? $non_duplicate_records : $duplicate_records);
        $this->write_a_csv_file($_wfilename, "w", "Utils::dump_records");
    }

    public function find_grouping_records($_filename, $_write_flag = 0, $_mode = 'r') {
        $this->fp = fopen($_filename, $_mode);

        $grouped = [];
        $grouped_ids = [];
        while(($data = fgetcsv($this->fp, 1000, ",", "\"", "\\")) !== false) {
            $grouped[$data[1]][] = $data;
            $grouped_ids[$data[1]][] = $data[0];
        }
        fclose($this->fp);

        print_r($grouped_ids);

        if($_write_flag) {
            $this->set_data_rows($grouped);
            $this->write_a_csv_file("outputs/groupings.csv", "w", "Utils::dump_group_records");
        }
    }

    public function find_target_records($_source_filename, $_destination_filename, $_mode = 'r') {
        $this->fp = fopen($_destination_filename, $_mode);

        $target_parts = [];
        while(($data = fgetcsv($this->fp, 1000, ",", "\"", "\\")) !== false) {
            $target_parts[$data[0]] = $data[0];
        }
        fclose($this->fp);

        $fp = fopen($_source_filename, $_mode);
        $target_matched_records = [];
        while(($data = fgetcsv($fp, 1000, ",", "\"", "\\")) !== false) {
            if (array_key_exists($data[0], $target_parts)) { 
                $target_matched_records[] = $data;
            }
        }
        fclose($fp);

        $this->set_data_rows($target_matched_records);
        $this->write_a_csv_file("outputs/target_records.csv", "w", "Utils::dump_records");
    }
}