<?php

namespace Application\Helper;

use Laminas\Db\Adapter\AdapterInterface;

class TranslationHelper
{
    private $dbAdapter;

    public function __construct(AdapterInterface $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
    }

    public function getTranslation($key, $locale)
    {
        // Adjust the column to fetch based on the locale
        

        // Prepare SQL statement
        $sql = "SELECT `$locale` FROM translations WHERE `origin` = ? LIMIT 1";
        $statement = $this->dbAdapter->createStatement($sql, [$key]);
        $result = $statement->execute();

        if ($result->count() > 0) {
            return $result->current()[$locale]; // Use the correct column
        }

        return $key; // Return the key if no translation is found
    }
}