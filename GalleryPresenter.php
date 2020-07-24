<?php

namespace FrontModule;

use Nette;
use Nette\Utils\Validators;
use App\Model\DatabaseModel;


final class GalleryPresenter extends Nette\Application\UI\Presenter
{

    private $databaseModel;
    public function __construct(DatabaseModel $databaseModel){
        $this->databaseModel = $databaseModel;
    }

    public function renderGallery($gallery){
        if(Validators::isNumeric($gallery)){
            $display = $this->databaseModel->getGallery($gallery);
            if(empty($display)){
                $this->error('You Shall Not Pass!', Nette\HTTP\IResponse::S403_FORBIDDEN);
            }
            $this->template->gallery = $gallery; 
            
            $col = $this->databaseModel->columnate($gallery);
            $this->template->col = $col;

            $files = $this->databaseModel->listPhotos($gallery);
            $this->template->files = $files;
        } else {
            $this->error('You Shall Not Pass!', Nette\HTTP\IResponse::S403_FORBIDDEN);
        }
    }

}
