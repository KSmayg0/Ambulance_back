<?php


namespace App\Models\Projects;

use App\Models\Model;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\ConditionalFormatting\Wizard;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelModel extends Model
{
    #Взять все заявки
    public function getRequests(): array|bool|null {


        $query = "SELECT  req.id as request_id, req.description as request_description, req.user_id as user_id, 
       req.chat_id as chat_id, req.date as date_create, s.name as status_name, p.name as priority_name,
       req.date_expired as date_expired, req.arhive as arhive, req.worker_id as worker_id
    FROM requests req
    LEFT JOIN status s on s.id = req.status_id
    LEFT JOIN priority p on p.id = req.priority_id
    ORDER BY date_create ASC, priority_name DESC;";

        return $this->createRequestArray($query);
    }

    #Взять все заявки, которые находятся вне архива
    public function getRequestsNonArhive(): array|bool|null {

        $query = "SELECT  req.id as request_id, req.description as request_description, req.user_id as user_id, 
       req.chat_id as chat_id, req.date as date_create, s.name as status_name, p.name as priority_name,
       req.date_expired as date_expired, req.arhive as arhive, req.worker_id as worker_id
    FROM requests req
    LEFT JOIN status s on s.id = req.status_id
    LEFT JOIN priority p on p.id = req.priority_id
    WHERE arhive = 0
    ORDER BY date_create ASC, priority_name DESC;";

        return $this->createRequestArray($query);
    }

    #Все заявки, которые находятся в архиве
    public function getRequestsArhive(): array|bool|null {

        $query = "SELECT  req.id as request_id, req.description as request_description, req.user_id as user_id, 
       req.chat_id as chat_id, req.date as date_create, s.name as status_name, p.name as priority_name,
       req.date_expired as date_expired, req.arhive as arhive, req.worker_id as worker_id
    FROM requests req
    LEFT JOIN status s on s.id = req.status_id
    LEFT JOIN priority p on p.id = req.priority_id
    WHERE arhive = 1
    ORDER BY date_create ASC, priority_name DESC;";

        return $this->createRequestArray($query);
    }

    #Создать файл excel по всем заявкам
public function createExcel(): void {
    $requests=$this->getRequests();
    $spreadsheet = new Spreadsheet();
    $activeWorksheet = $spreadsheet->getActiveSheet();

    header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
    header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
    header ( "Cache-Control: no-cache, must-revalidate" );
    header ( "Pragma: no-cache" );
    header ( "Content-type: application/vnd.ms-excel" );
    header ( "Content-Disposition: attachment; filename=requests.xlsx" );

    $writer = new Xlsx($this->formationExcel($requests, $activeWorksheet, $spreadsheet));
    $writer->save('php://output');

}

    #Создать файл excel по заявкам, которые находятся вне архива
    public function createExcelNonArhive(): void {
        $requests=$this->getRequestsNonArhive();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();

        header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
        header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
        header ( "Cache-Control: no-cache, must-revalidate" );
        header ( "Pragma: no-cache" );
        header ( "Content-type: application/vnd.ms-excel" );
        header ( "Content-Disposition: attachment; filename=requests_non_arhive.xlsx" );

        $writer = new Xlsx($this->formationExcel($requests, $activeWorksheet, $spreadsheet));
        $writer->save('php://output');

    }

    #Создать файл excel по заявкам, которые находятся в архиве
    public function createExcelArhive(): void {
        $requests=$this->getRequestsArhive();
        $spreadsheet = new Spreadsheet();
        $activeWorksheet = $spreadsheet->getActiveSheet();


        header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
        header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
        header ( "Cache-Control: no-cache, must-revalidate" );
        header ( "Pragma: no-cache" );
        header ( "Content-type: application/vnd.ms-excel" );
        header ( "Content-Disposition: attachment; filename=requests_arhive.xlsx" );

        $writer = new Xlsx($this->formationExcel($requests, $activeWorksheet, $spreadsheet));
        $writer->save('php://output');

    }

    #Взять id пользователя
    private function getUserId(): array{
        $query="SELECT user_id FROM ForBot;";
        $user_id=$this->getAllWithNoParamsBot($query);
        $user_id_str=array();
        foreach($user_id as $user) {
            foreach($user as $id) {
                array_push($user_id_str,$id);
            }
        }
        return $user_id_str;
    }

    #Взять имя пользователя
    private function getUserName(): array {
        #Взять имена и фамилии пользователей, оставивших заявки
        $user_id=implode("','",$this->getUserId());
        //Берём имена пользователей из базы
        $query1="SELECT CONCAT(lastname, ' ', name) AS name FROM users_autent WHERE id IN ('$user_id')";
        $user_name = $this->getAllWithNoParams($query1);
        $user_name1=array();
        foreach($user_name as $user) {
            foreach($user as $arr) {
                array_push($user_name1, $arr);
            }
        }
        return $user_name1;
    }

    private function getWorkerId(): array {
        //Берём wroker_id из таблицы с сотрудниками
        $query="SELECT id FROM users_autent WHERE role_id=1;";
        $worker_id=$this->getAllWithNoParams($query);
        $worker_id_str=array();
        foreach($worker_id as $worker) {
            foreach($worker as $id) {
                array_push($worker_id_str,$id);
            }
        }
        return $worker_id_str;
    }

    private function getWorkerName(): array {
        #Взять имена и фамилии исполнителей заявки
        $worker_id=implode("','",$this->getWorkerId());
        //Берём имена сотрудников из базы
        $query1="SELECT CONCAT(lastname, ' ', name) AS name FROM users_autent WHERE (id IN ('$worker_id')) && role_id=1;";
        $worker_name = $this->getAllWithNoParams($query1);
        $worker_name1=array();
        foreach($worker_name as $worker) {
            foreach($worker as $arr) {
                array_push($worker_name1, $arr);
            }
        }
        return $worker_name1;
    }

    private function createRequestArray(string $query): array {
        $requests = $this->getAllWithNoParamsBot($query);

        $name_id=array_combine($this->getUserId(),$this->getUserName());

        $worker= array_combine($this->getWorkerId(),$this->getWorkerName());
        $copyarray = $requests;
        foreach($copyarray as $key => $value) {
            $requests[$key]['user_name_surname'] = $name_id[$value['user_id']];
        }
        foreach($copyarray as $key => $value) {
            if($requests[$key]['worker_id']!=null) {
                $requests[$key]['worker_name_surname'] = $worker[$value['worker_id']];
            }
        }
        return $requests;
    }

    private function createRequestArrayUser(string $query, $request_id): array {
        $requests = $this->getByIdFormBot($query, $request_id);

        $name_id=array_combine($this->getUserId(),$this->getUserName());

        $copyarray = $requests;
        foreach($copyarray as $key => $value) {
            $requests[$key]['user_name_surname'] = $name_id[$value['user_id']];
        }
        return $requests;
    }

    private function formationExcel($requests, $activeWorksheet, $spreadsheet) {
        $activeWorksheet->setTitle('Requests');
        $spreadsheet->getDefaultStyle()
            ->getFont()
            ->setName('Arial')
            ->setSize(12);
        $title=array(
            'ID',
            'Текст заявки',
            'ID Чата',
            'Дата создания заявки',
            'Статус заявки',
            'Приоритет',
            'Дата выполнения заявки',
            'Архив',
            'Имя пользователя',
            'Имя сотрудника',
        );
        $ascii=ord("A");
        $row=1;
        foreach ($title as $value) {
            $activeWorksheet->setCellValue(chr($ascii) . $row, $value);
            $activeWorksheet->getColumnDimension(chr($ascii))->setAutoSize(true) ;
            $activeWorksheet->getStyle(chr($ascii) . $row)->getFont()->setBold(true);
            $ascii++;
        }

        $activeWorksheet->getStyle("A1:L1")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $activeWorksheet->getStyle("A1:L1")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $ascii=ord("A");
        $row=2;
        foreach ($requests as $key) {
            $count=1;
            foreach ($key as $value) {
                if(($count!=3) && ($count!=10)) {
                    if($value===NULL) {
                        $value="Не назначено";
                    }
                    $activeWorksheet->getStyle(chr($ascii) . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                    if($value=="Не назначено") {
                        $activeWorksheet->getStyle(chr($ascii) . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color( \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_RED ));
                    }
                    switch($value) {
                        case "Новая заявка": $activeWorksheet->getStyle(chr($ascii) . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color( \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLUE ));
                            break;
                        case "В работе": $activeWorksheet->getStyle(chr($ascii) . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color( \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKRED ));
                            break;
                        case "Выполнена": $activeWorksheet->getStyle(chr($ascii) . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color( \PhpOffice\PhpSpreadsheet\Style\Color::COLOR_DARKGREEN ));
                            break;
                    }
                    $activeWorksheet->setCellValue(chr($ascii) . $row, $value);
                    $ascii++;
                }
                $count++;


            }
            $ascii=ord("A");
            $row++;
        }
        return $spreadsheet;
    }
}