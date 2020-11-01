# phpMergeDocx

## Description
These functions are intended to merge docx files for LibreOffice in php. It's based on a comment of stackoverflow (https://stackoverflow.com/a/7960164/7494347). This project is born due to an unresolved issue of `krustnic/DocxMerge` (https://github.com/krustnic/DocxMerge) where merged document only contains first document on LibreOffice.

## Known issue
Only works for LibreOffice. The document won't open with MS Word. I'm convinced that with little changes it can work on both.

## Usage
```
include_once("phpMergeDocx.php");

$files_to_merge = array("document1.docx", "document2.docx", "document3.docx");
$retcode = mergeDocx($files_to_merge, "merged.docx");
if($retcode === 0){
    echo "Files merged sucessfully!";
}
else{
    die("An error has occurred.");
}

```

## External library
*  tbszip (http://www.tinybutstrong.com) : LPGL