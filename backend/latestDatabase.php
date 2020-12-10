<?php 

class latestDatabase 
{
    public function getDb()
    {
        // Get the latest file from the database folder - files are time-stamped
        $dir = "database/";
        $files = scandir($dir);
        
        $files = $this->getLatestFile();
        
        $newest_file = $files[0];
        return $newest_file;        
    }

    public function getLatestFile()
    {
        $files = array_filter(scandir('database/', 1), function($item) {
            return $item[0] !== '.';
        });
        return $files;
    }

}
