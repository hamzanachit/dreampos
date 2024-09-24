<?php

namespace Application\Helper;

use Laminas\Db\Adapter\AdapterInterface;
use GuzzleHttp\Client;


class TranslationHelper{
    private $dbAdapter;
    private $collectedKeys = [];
    

    public function __construct(AdapterInterface $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
        
    }
      public function __invoke($key)
    {
        return $this->getTranslation($key );
    }


  public function getTranslation($key  ){
  

     $locale ="";
    $langue = $this->getLocaleFromSettings();
    if($langue === "English"){ 
        $locale ==="origin";
    }else if($langue ==="French"){ 
        $locale ="Fr";

    }else if ($langue ="Arabe"){ 
        $locale ="Ar";

    }else{
        $locale ="En";


    }
    $sql = "SELECT `$locale` FROM translations WHERE `origin` = ? LIMIT 1";
    $statement = $this->dbAdapter->createStatement($sql, [$key]);
    
    try {
        $result = $statement->execute();
        if ($result->count() > 0) {
            $translation = $result->current()[$locale];
            $this->collectedKeys[$key][$locale] = $translation; // Cache the translation
            return $translation; // Return the fetched translation
        }
    } catch (\Exception $e) {
        // Optional: Log the error message
        // error_log($e->getMessage());
    }

    return $key; // Return the key if no translation is found or an error occurs
}


    // Helper method to get locale from the settings table
    private function getLocaleFromSettings(){
         $sql = "SELECT `language` FROM setting WHERE `company_status` = 'actif' LIMIT 1"; // Adjust the key as needed
        $statement = $this->dbAdapter->createStatement($sql);
        
        try {
            $result = $statement->execute();
            if ($result->count() > 0) {
                return $result->current()['language']; // Return the language value
            }
        } catch (\Exception $e) {
            // Optional: Log the error message
            // error_log($e->getMessage());
        }

        return null; // Return null if no locale is found or an error occurs
    }







}