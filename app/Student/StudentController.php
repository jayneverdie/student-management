<?php

namespace App\Student;

use App\Student\StudentAPI;
use App\Common\View;
use App\Common\Datatables;
use App\Common\Tool;

class StudentController
{
  public function __construct() {
    $this->student = new StudentAPI;
    $this->view = new View;
    $this->datatables = new Datatables;
    $this->tool = new Tool;
  }

  public function ViewStudent($request, $response, $args) {
    return $this->view->render('pages/student/student');
  }

  public function ViewPromote($request, $response, $args) {
    return $this->view->render('pages/student/promote');
  }

  public function all($request, $response, $args) {
    $parsedBody = $request->getParsedBody();

    $data = $this->student->all($this->datatables->filter($parsedBody));
    $pack = $this->datatables->get($data, $parsedBody);

    return $response->withJson($pack);
  }

  public function allPromote($request, $response, $args) {
    $parsedBody = $request->getParsedBody();
    $params = $request->getQueryParams();
    $c_af = $params["c_af"];
    $c_bf = $params["c_bf"];

    $data = $this->student->allPromote($c_af,$c_bf);
    $pack = $this->datatables->get($data, $parsedBody);

    return $response->withJson($pack);
  }

  public function allPromoteTemp($request, $response, $args) {
    $parsedBody = $request->getParsedBody();
    $params = $request->getQueryParams();

    $data = $this->student->allPromoteTemp();
    $pack = $this->datatables->get($data, $parsedBody);

    return $response->withJson($pack);
  }

  public function allBy($request, $response, $args) {
    $parsedBody = $request->getParsedBody();
    $params = $request->getQueryParams();
    $id = $params["id"];
    $date = $params["date"];
    $form_type = $params["form_type"];
    $date = date('Y-m-d', strtotime($date));

    $data = $this->student->allBy($params["id"],$date,$form_type);
    $pack = $this->datatables->get($data, $parsedBody);

    return $response->withJson($pack);
  }

  public function getNameFix($request, $response, $args) {
    try {
      return $response->withJson($this->student->getNameFix());
    } catch (\Exception $e) {
      return [];
    }
  }

  public function getSex($request, $response, $args) {
    try {
      return $response->withJson($this->student->getSex());
    } catch (\Exception $e) {
      return [];
    }
  }

  public function getEducation($request, $response, $args) {
    try {
      return $response->withJson($this->student->getEducation());
    } catch (\Exception $e) {
      return [];
    }
  }

  public function getClassroom($request, $response, $args) {
    try {
      return $response->withJson($this->student->getClassroom());
    } catch (\Exception $e) {
      return [];
    }
  }

  public function create($request, $response, $args) {
    try {
      $parsedBody = $request->getParsedBody();
      $root = 'files/document/student/';
      $rootimg = 'files/images/student/';
      
      if (isset($_FILES["files_upload"]["name"])) {
        $this->tool->initFolder($root, $parsedBody['card_id']);
        for( $i=0; $i < count($_FILES["files_upload"]["name"] ); $i++ ){
          $sur = $_FILES['files_upload']['name'][$i];
          $typename = strrchr($sur,".");
          $newfilename = "student_".$parsedBody["card_id"]."_".date('dmY_His')."_".$i.$typename;
          move_uploaded_file($_FILES["files_upload"]["tmp_name"][$i],iconv('UTF-8','windows-874',$root.$parsedBody['card_id'].'/'.$newfilename));
          $result = $this->student->logsfile(
            $newfilename,'student',$parsedBody['card_id']
          );
        }
      }

      if (isset($_FILES["image_student"]["name"])) {
        $this->tool->initFolder($rootimg, $parsedBody['card_id']);
        $sur = $_FILES['image_student']['name'];
        $typename = strrchr($sur,".");
        move_uploaded_file($_FILES["image_student"]["tmp_name"],iconv('UTF-8','windows-874',$rootimg.$parsedBody['card_id'].'/'.$parsedBody['card_id'].$typename));
      }

      $result = $this->student->create(
        $parsedBody["card_id"],
        $parsedBody["nickname"],
        $parsedBody["name_prefix"],
        $parsedBody["student_name"],
        $parsedBody["student_lastname"],
        $parsedBody["sex_id"],
        $parsedBody["birthday"],
        $parsedBody["phone"],
        $parsedBody["attendance"],
        $parsedBody["classroom"],
        $parsedBody["address_first"],
        $parsedBody["address_second"],
        $parsedBody["remark"],
        $parsedBody["cardid_father"],
        $parsedBody["cardid_mother"]
      );

      return $response->withJson($result);
    } catch (\Exception $e) {
      return [];
    }
  }

  public function move($request, $response, $args) {
    try {
      $parsedBody = $request->getParsedBody();

      $result = $this->student->move(
        $parsedBody["student_id"],
        $parsedBody["classroom_before"],
        $parsedBody["classroom_after"]
      );
      
      return $response->withJson($result);
    } catch (\Exception $e) {
      return [];
    }
  }

  public function moveConfirm($request, $response, $args) {
    try {
      $parsedBody = $request->getParsedBody();

      $result = $this->student->moveConfirm();
      
      return $response->withJson($result);
    } catch (\Exception $e) {
      return [];
    }
  }

  public function readCard($request, $response, $args) {
    try {
      $result = $this->tool->readCard();
      return $result;
    } catch (\Exception $e) {
      return [];
    }
  }

}

