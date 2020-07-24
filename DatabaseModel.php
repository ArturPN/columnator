<?php

namespace App\Model;
use Nette;
use Nette\Utils\FileSystem;
use Nette\Utils\Finder;
use Nette\Utils\Image;

class DatabaseModel {

    private $database;

    public function __construct(Nette\Database\Context $database){
        $this->database = $database;
    }

    public function identityStatus($pres){
        if($pres->getUser()->isLoggedIn() == 1){
            $identityStatus = $pres->getUser()->getIdentity()->username;
        } else {
            $identityStatus = FALSE;
        }
        return $identityStatus;
    }

    public function getGalleries(){
        $galleries = $this->database->table('galleries');
        return $galleries;
    }

    public function getGallery($gallery){
        $galleries = $this->database->table('galleries')->where('ID = '.$gallery.'')->fetch();
        return $galleries;
    }
    
    public function columnate($gallery){
        /*
            THIS FUNCION RETURNS AN ARRAY $col WHICH IS USED TO PRESENT PHOTOS IN 4 COLUMNS.
            I recommend saving the results into a database or a file due to performance issues - we don't want this script to iterate through photos
            every time user enters the website. Do it once - during uploading photos.
        */

        // define start variables
        $files = array();
        $rowInColumn = array(1 => 1, 2=> 1, 3=>1, 4=>1);
        $col[1] = array();
        $col[2] = array();
        $col[3] = array();
        $col[4] = array();
        $column = 1;
        $nmbr = 1;
        $photo = 0;
        $dir = __DIR__.'\..\..\www\gallery-imgs\cat'.$gallery.'\mini\\';
        $path = '/gallery-imgs/cat'.$gallery.'/mini/';

        // find photos in catalogue $dir and assign them to columns $col
        foreach(Finder::findFiles('*.jpg')->in($dir) as $file){
            $img = Image::fromFile($dir.$nmbr.'-mini.jpg');
            $or = $img->getWidth()/$img->getHeight();

            // for photos rotated only by EXIF data (for example, mobile phones or without developing)
            $exif = exif_read_data($dir.$nmbr.'-mini.jpg');
            if(empty($exif['Orientation'])){
                $exifOrnt = NULL;
            } else {
                $exifOrnt = $exif['Orientation'];
            }
            if($exifOrnt == 6 OR $exifOrnt == 8 OR $exifOrnt == 5 OR $exifOrnt == 7){
                if($or > 1){
                    $or = 0.5;
                }
            }

            // determine if selected column has the lowest number - if not, find the column with lowest free row
            $lowestRow = min($rowInColumn);
            if($lowestRow < $rowInColumn[$column]){
                $column = min(array_keys($rowInColumn, min($rowInColumn)));
            }

            // assign the photo to chosen column
            $col[$column][] = $photo;

            // define last avaliable row in exact column - will be used during next iteration for this column
            if($or < 1){
                $rowInColumn[$column] = $rowInColumn[$column]+2;
            } else {
                $rowInColumn[$column] = $rowInColumn[$column]+1;
            }
            
            // just for our foreach...
            $column++;
            if($column >= 5){ $column = 1; }
            $photo++;
            $nmbr++;
        }

        return $col;
    }

    public function listPhotos($gallery){
        /* 
            Create an array $files containing pathes $path to every photo in the directory $dir
        */

        $files = array();
        $nmbr = 1;
        $dir = __DIR__.'\..\..\www\gallery-imgs\cat'.$gallery.'\mini\\';
        $path = '/gallery-imgs/cat'.$gallery.'/mini/';

        foreach(Finder::findFiles('*.jpg')->in($dir) as $file){
            $files[] = $path.$nmbr.'-mini.jpg';
            $nmbr++;
        }

        return $files;
    }
}


