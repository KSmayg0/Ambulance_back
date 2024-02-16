<?php


namespace App\Controllers;
use App\Models\Insctructions\InstructionsCatalogModel;
use App\Models\Management\DepartmentModel;
use App\Models\Management\GroupModel;
use App\Models\SerivceEntity\Floor;
use App\Models\SerivceEntity\Room;
use App\Models\Users\UserModel;
use App\Views\Management\ManagementView;

class ManagementController extends Controller
{

    private ManagementView $managementView;
    private InstructionsCatalogModel $instructModel;

    public function __construct() {
        parent::__construct();
        $this->managementView = new ManagementView();
        $this->instructModel = new InstructionsCatalogModel();
    }

    public function userManagement(): void {
        $userModel = new UserModel();
        $floorModel = new Floor();
        $floors = $floorModel->getAllFloorsWhereRoomsNotNull();
        $users = $userModel->getAllUsersForManagement();
        $this->managementView->userManagement($users, $floors);
    }

    public function userManagementCreate(): void  {
//        debugArrayThenDie($_POST['dataToSend']);
        $userModel = new UserModel();
        $userModel->createUser($_POST['dataToSend']);
        $floorModel = new Floor();
        $floors = $floorModel->getAllFloorsWhereRoomsNotNull();
        $users = $userModel->getAllUsersForManagement();
        $this->managementView->userManagement($users, $floors);
    }

    public function userManagementUpdate(): void {
        $userModel = new UserModel();
        $userModel->updateUserById($_POST['dataToSend']);
        $floorModel = new Floor();
        $floors = $floorModel->getAllFloorsWhereRoomsNotNull();
        $users = $userModel->getAllUsersForManagement();
        $this->managementView->userManagement($users, $floors);
    }

    public function updateModalLoad(): void {
        $userModel = new UserModel();
        $floorModel = new Floor();
        $floors = $floorModel->getAllFloorsWhereRoomsNotNull();
        $user = $userModel->getUserForManagementById($_POST['dataToSend']);
        $this->managementView->updateModalLoad($user, $floors);
    }

    public function userManagementDelete(): void {
        $userModel = new UserModel();
        $userModel->deleteUserById($_POST['dataToSend']);
        $floorModel = new Floor();
        $floors = $floorModel->getAllFloorsWhereRoomsNotNull();
        $users = $userModel->getAllUsersForManagement();
        $this->managementView->userManagement($users, $floors);
    }

    public function getRoomsForSelect(): void {
        $roomsModel = new Room();
        $rooms = $roomsModel->getAllRoomsByFloorId($_POST['dataToSend']);
        $this->managementView->getRoomsForSelect($rooms);
    }

    public function getRoomsForSelectUpdate(): void {
        $roomsModel = new Room();
        $rooms = $roomsModel->getAllRoomsByFloorId($_POST['dataToSend']['floor_id']);
        $this->managementView->getRoomsForSelectUpdate($rooms, $_POST['dataToSend']['floor_change']);
    }
    public function getDepartmentsForSelect(): void {
        $departmentModel = new DepartmentModel();
        $departments = $departmentModel->getAllDepartmentsByFloorId($_POST['dataToSend']);
        $this->managementView->getDepartmentsForSelect($departments);
    }

    public function getDepartmentsForSelectUpdate(): void {
        $departmentModel = new DepartmentModel();
        $departments = $departmentModel->getAllDepartmentsByFloorId($_POST['dataToSend']);
        $this->managementView->getDepartmentsForSelectUpdate($departments);
    }

    public function groupManagement(): void
    {
        $groupModel = new GroupModel();
        $groups = $groupModel -> getAllGroups();
        $this->managementView->groupManagement($groups);
    }

    public function groupManagementCreate(): void
    {
        $groupModel = new GroupModel();
        $groupName = $_POST['name'];
        $groupModel -> createGroup($groupName);
        $groups = $groupModel -> getAllGroups();
        $this -> managementView -> groupManagement($groups);
    }

    public function groupManagementUpdate(): void
    {
        $groupModel = new GroupModel();
        $id = $_POST['id'];
        $groupName = $_POST['name'];
        $groupModel -> updateGroupById($id, $groupName);
        $groups = $groupModel -> getAllGroups();
        $this -> managementView -> groupManagement($groups);
    }

    public function groupManagementDelete(): void
    {
        $groupModel = new GroupModel();
        $id = $_POST['id'];
        $groupModel -> deleteGroupById($id);
        $groups = $groupModel -> getAllGroups();
        $this -> managementView -> groupManagement($groups);
    }


    public function departmentManagement(): void {
        $departmentModel = new DepartmentModel();
        $departments = $departmentModel->getAllDepartments();
        $this->managementView->departmentManagement($departments);
    }
    public function departmentManagementCreate(): void {
        $departmentModel = new DepartmentModel();
        $departmentName = $_POST['dataToSend'];
        $departmentModel->createDepartment($departmentName);
        $departments = $departmentModel->getAllDepartments();
        $this->managementView->departmentManagement($departments);
    }
    public function departmentManagementUpdate(): void {
        $departmentModel = new DepartmentModel();
        $id = $_POST['dataToSend']['id'];
        $departmentName = $_POST['dataToSend']['name'];
        $departmentModel->updateDepartmentById($id, $departmentName);
        $departments = $departmentModel->getAllDepartments();
        $this->managementView->departmentManagement($departments);
    }
    public function departmentManagementDelete(): void {
        $departmentModel = new DepartmentModel();
        $id = $_POST['dataToSend'];
        $departmentModel->deleteDepartmentById($id);
        $departments = $departmentModel->getAllDepartments();
        $this->managementView->departmentManagement($departments);
    }

    public function getOfficeStructure(): void {
        $phones = $this->instructModel->getAllPhones();
        $this->managementView->getOfficeStructure($phones);
    }

    public function createNewRoom(): void {
        $newRoomName = $_POST['dataToSend']['newRoomName'];
        $floor_id = $_POST['dataToSend']['floor_id'];
        $floorModel = new Floor();
        $floorModel->createNewRoomOnFloor($floor_id, $newRoomName);
        $phones = $this->instructModel->getAllPhones();
        $this->managementView->getOfficeStructure($phones);
    }

    public function deleteRoom(): void {
        $room_id = $_POST['dataToSend'];
        $roomModel = new Room();
        $roomModel->deleteRoomById($room_id);
        $phones = $this->instructModel->getAllPhones();
        $this->managementView->getOfficeStructure($phones);
    }

    public function editRoomModal(): void {
        $room_id = $_POST['dataToSend'];
        $departmentModel = new DepartmentModel();
        $departments = $departmentModel->getAllDepartments();
        $departmentsInRoom = $departmentModel->getAllDepartmentsByFloorId($room_id);
        $this->managementView->roomModalShow($room_id, $departmentsInRoom, $departments);
    }

    public function addDepartmentToRoom(): void {
        $room_id = $_POST['dataToSend']['room_id'];
        $department_id = $_POST['dataToSend']['department_id'];
        $room = new Room();
        $room->addDepartmentToRoom($room_id, $department_id);
        $phones = $this->instructModel->getAllPhones();
        $this->managementView->getOfficeStructure($phones);
    }

    public function deleteDepartmentFromRoom(): void
    {
        $room_id = $_POST['dataToSend']['room_id'];
        $department_id = $_POST['dataToSend']['department_id'];
        $room = new Room();
        $room->deleteDepartmentFromRoom($room_id, $department_id);
        $phones = $this->instructModel->getAllPhones();
        $this->managementView->getOfficeStructure($phones);
    }
}