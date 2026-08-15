<?php
function readDocx($filename) {
    $zip = new ZipArchive;
    if ($zip->open($filename) === true) {
        if (($index = $zip->locateName('word/document.xml')) !== false) {
            $data = $zip->getFromIndex($index);
            $zip->close();
            
            $doc = new DOMDocument();
            $doc->loadXML($data, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
            return strip_tags($doc->saveXML());
        }
        $zip->close();
    }
    return false;
}
echo readDocx($argv[1]);
