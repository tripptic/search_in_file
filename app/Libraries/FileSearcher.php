<?php
namespace App\Libraries;


class FileSearcher
{
    const FILE_MAX_SIZE = 10000000;

    const ALLOWED_MIME_TYPES = [
        "text/plain",
        "text/html",
        "text/csv",
        'application/octet-stream',
        'application/xml',
    ];

    /**
     * Find string in file
     *
     * @param string $filename
     * @param string $string
     * @return array|bool Array with line and position, or false if there are errors.
     */
    public static function findString($filename, $string) {
        if (file_exists($filename)
            && filesize($filename) < self::FILE_MAX_SIZE
            && in_array(mime_content_type($filename), self::ALLOWED_MIME_TYPES)
        ) {
            $handle = fopen($filename, "r");
            if ($handle)
            {
                $lineNum = 0;
                while (!feof($handle))
                {
                    $buffer = fgets($handle);
                    $position = mb_strpos($buffer, $string);
                    if ($position !== FALSE) {
                        return [
                            $lineNum,
                            $position
                        ];
                    }
                    $lineNum++;
                }
                fclose($handle);
            }
        }

        return false;
    }
}