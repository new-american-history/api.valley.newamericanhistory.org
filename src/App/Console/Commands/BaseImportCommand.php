<?php

namespace App\Console\Commands;

use DOMDocument;
use Illuminate\Console\Command;

class BaseImportCommand extends Command
{
    protected $signature = 'import';

    protected $description = 'Base class for data import commands';

    public function getFileData($file)
    {
        $path = storage_path() . '/import-data/' . $file;
        return file_get_contents($path);
    }

    public function getDomDocumentWithHtml($html)
    {
        $errorsToSkip = ['Unexpected end tag : p'];
        return self::getDomDocument($html, 'loadHTML', $errorsToSkip);
    }

    public function getDomDocumentWithXml($xml)
    {
        return self::getDomDocument($xml, 'loadXML');
    }

    public function getDomDocument($data, $function, $errorsToSkip = [])
    {
        libxml_use_internal_errors(true);

        $document = new DOMDocument();
        $document->$function($data, LIBXML_HTML_NODEFDTD);

        foreach (libxml_get_errors() as $error) {
            if (!in_array(trim($error->message), $errorsToSkip)) {
                echo $error->message;
            }
        }
        libxml_use_internal_errors(false);

        return $document ?? null;
    }
}
