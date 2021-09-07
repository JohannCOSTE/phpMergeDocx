<?php
include_once('tbszip.php');

/**
 * phpMergeDocx version 1.0.0
 * Based on     : https://stackoverflow.com/a/7960164/7494347
 * Date         : 2020-11-01
 * Author       : JohannCOSTE
 * Licence      : LGPL (beacause use of LGPL tbszip library)
 * Description  : These functions are intended to merge docx files for LibreOffice in php.
 */

/**
 * @param string $document1
 * @param string $document2
 * @param string $outputfile
 * @return $outputfile if success, null otherwise
 */
function merge2Docx($document1, $document2, $outputfile)
{
    $pageBreak ='<w:p><w:pPr><w:sectPr><w:type w:val="nextPage" /></w:sectPr></w:pPr></w:p>';

    if (file_exists($document1) && file_exists($document2)) {
        $zip = new clsTbsZip();

        // Open the first document
        $zip->Open($document1);
        $content1 = $zip->FileRead('word'.DIRECTORY_SEPARATOR.'document.xml');
        $zip->Close();

        // Extract the content of the first document
        $p = strpos($content1, '<w:body');
        if ($p === false) {
            // Tag <w:body> not found in document 1
            return null;
        }
        $p = strpos($content1, '>', $p);
        $content1 = substr($content1, $p + 1);
        $p = strpos($content1, '</w:body>');
        if ($p === false) {
            // Tag </w:body> not found in document 1
            return null;
        }

        $content1 =  $pageBreak.substr($content1, 0, $p);

        // Insert into the second document
        $zip->Open($document2);
        $content2 = $zip->FileRead('word'.DIRECTORY_SEPARATOR.'document.xml');
        $p = strpos($content2, '</w:body>');
        if ($p === false) {
            // Tag </w:body> not found in document 2
            return null;
        }
        $content2 = substr_replace($content2, $content1, $p, 0);
        $zip->FileReplace('word'.DIRECTORY_SEPARATOR.'document.xml', $content2, 32);

        // Save the merge into a third file
        $zip->Flush(TBSZIP_FILE, $outputfile);

        return $outputfile;
    } else {
        // $document1 or $document2 doesn't exist
        return null;
    }
}

/**
 * @param string[] $documents
 * @param string $outputfile
 * @return 0 if success, null otherwise
 */
function mergeDocx($documents, $outputfile)
{
    $documents = array_reverse($documents);
    $nbDocx = count($documents);

    if ($nbDocx >= 2) {
        $previousMerge = merge2Docx($documents[0], $documents[1], $outputfile);
        for ($i = 2; $i < $nbDocx; $i++) {
            if ($previousMerge !== null) {
                $previousMerge = merge2Docx($previousMerge, $documents[$i], $outputfile);
            } else {
                return null;
            }
        }
        return 0;
    }
    elseif ($nbDocx == 1){
        copy($documents[0], $outputfile);
        return 0;
    }
    else{
        return null;
    }

}
