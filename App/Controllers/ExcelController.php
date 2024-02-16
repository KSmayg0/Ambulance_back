<?php


namespace App\Controllers;

use App\Models\Projects\ExcelModel;

use App\Views\Projects\ProjectView;


class ExcelController extends Controller
{
    private ProjectView $projectView;
    private ExcelModel $excelModel;

    public function __construct()
    {
        parent::__construct();
        $this->projectView = new ProjectView();
        $this->excelModel = new ExcelModel();
    }

    public function main(): void
    {
        $this->projectView->index();
    }

    #Создать excel файл списка заявок
    public function createExcel(): void {
        $this->excelModel->createExcel();
    }

    #Создать excel файл списка заявок, которые находятся вне архива
    public function createExcelNonArhive(): void {
        $this->excelModel->createExcelNonArhive();
    }

    #Создать excel файл списка заявок, которые находятся в архиве
    public function createExcelArhive(): void {
        $this->excelModel->createExcelArhive();
    }
}