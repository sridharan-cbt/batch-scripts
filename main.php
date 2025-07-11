<?php

require_once 'FileHandler.php';
require_once 'Utils.php';

$products = ["mouse", "keyboard", "pen-drive", "headset"];
$languages = ["fr", "en", "ta", "ja"];

$fh = new FileHandler();

$count = readline("Enter the number: ");
for($i=0; $i<$count; $i++) {
    $row = array(
        Utils::random_number_generator(), 
        $languages[array_rand($languages)],
        $products[array_rand($products)],
        "Lorem ipsum dolor sit amet consectetur adipiscing elit."
    );

    $fh->rows[] = $row;
}

$fh->dump_records("csv/data.csv", 'a');
$fh->find_duplicate_records("csv/data.csv", "outputs/duplicates-record.csv", 'r');
$fh->find_grouping_records("csv/data.csv", 1);
$fh->find_target_records("csv/data.csv", "csv/target.csv");
