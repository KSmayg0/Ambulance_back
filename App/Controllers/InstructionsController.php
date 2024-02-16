<?php

namespace App\Controllers;

use App\Models\Insctructions\InstructionsCatalogModel;
use App\Views\Instructions\InstructionView;

class InstructionsController extends Controller {
    private InstructionView $render;
    private InstructionsCatalogModel $instructModel;

    public function __construct() {
        parent::__construct();
        $this->render = new InstructionView();
        $this->instructModel = new InstructionsCatalogModel();
    }

    public function main() {
        $this->render->index();
    }

    public function instDetails() {
        $catalog = $this->instructModel->getFullCatalog();
        $this->render->instDetails($catalog);
    }

    public function loadContent() {
        $id = $_POST['id'];
        $content = $this->instructModel->getFullContentOfCatalog($id);
        $this->render->instContentOfCatalog($content);
    }

    public function phoneDetails() {
        $phones = $this->instructModel->getAllPhones();
//        debugArrayThenDie($phones);
        $this->render->phoneDetails($phones);
    }

}