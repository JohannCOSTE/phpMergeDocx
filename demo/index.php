<?php
include_once("../phpMergeDocx.php");

$files_to_merge = array("document1.docx", "document2.docx", "document3.docx");
$retcode = mergeDocx($files_to_merge, "merged.docx");
if($retcode === 0){
    echo "Files merged sucessfully!";
}
else{
    die("An error has occurred.");
}
