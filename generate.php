<?php
require_once "vendor/autoload.php";

$options = getopt("s:d:");
$source = $options["s"];
$destination = $options["d"];
if($source == "" || $destination == "") {
    echo "Usage : php generate.php -s {source} -d {destination}\n";
    exit();
}

$t = new ClassTemplating();
$a = new PhpUnitSkeletonGenerator($t);
$a->generateSkeleton($source , $destination);
