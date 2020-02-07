<?php

namespace App\Teacher;

use App\Teacher\TeacherAPI;
use App\Common\View;
use App\Common\Datatables;
use App\Common\Tool;

class TeacherController
{
  public function __construct() {
    $this->teacher = new TeacherAPI;
    $this->view = new View;
    $this->datatables = new Datatables;
    $this->tool = new Tool;
  }

  public function ViewTeacher($request, $response, $args) {
    return $this->view->render('pages/teacher/teacher');
  }

  public function all($request, $response, $args) {
    $parsedBody = $request->getParsedBody();

    $data = $this->teacher->all($this->datatables->filter($parsedBody));
    $pack = $this->datatables->get($data, $parsedBody);

    return $response->withJson($pack);
  }

  public function getNameFix($request, $response, $args) {
    try {
      return $response->withJson($this->teacher->getNameFix());
    } catch (\Exception $e) {
      return [];
    }
  }

  public function getSex($request, $response, $args) {
    try {
      return $response->withJson($this->teacher->getSex());
    } catch (\Exception $e) {
      return [];
    }
  }

  public function getEducation($request, $response, $args) {
    try {
      return $response->withJson($this->teacher->getEducation());
    } catch (\Exception $e) {
      return [];
    }
  }

  public function getCareer($request, $response, $args) {
    try {
      return $response->withJson($this->teacher->getCareer());
    } catch (\Exception $e) {
      return [];
    }
  }

  public function create($request, $response, $args) {
    try {
      $parsedBody = $request->getParsedBody();
      $root = 'files/document/teacher/';
      $rootimg = 'files/images/teacher/';
      
      if (isset($_FILES["files_upload"]["name"])) {
        $this->tool->initFolder($root, $parsedBody['card_id']);
        for( $i=0; $i < count($_FILES["files_upload"]["name"] ); $i++ ){
          $sur = $_FILES['files_upload']['name'][$i];
          $typename = strrchr($sur,".");
          $newfilename = "teacher_".$parsedBody["card_id"]."_".date('dmY_His')."_".$i.$typename;
          move_uploaded_file($_FILES["files_upload"]["tmp_name"][$i],iconv('UTF-8','windows-874',$root.$parsedBody['card_id'].'/'.$newfilename));
          $result = $this->teacher->logsfile(
            $newfilename,'teacher',$parsedBody['card_id']
          );
        }
      }

      if (isset($_FILES["image_teacher"]["name"])) {
        $this->tool->initFolder($rootimg, $parsedBody['card_id']);
        $sur = $_FILES['image_teacher']['name'];
        $typename = strrchr($sur,".");
        move_uploaded_file($_FILES["image_teacher"]["tmp_name"],iconv('UTF-8','windows-874',$rootimg.$parsedBody['card_id'].'/'.$parsedBody['card_id'].$typename));
      }

      $result = $this->teacher->create(
        $parsedBody["card_id"],
        $parsedBody["name_prefix"],
        $parsedBody["teacher_name"],
        $parsedBody["teacher_lastname"],
        $parsedBody["sex_id"],
        $parsedBody["birthday"],
        $parsedBody["phone"],
        $parsedBody["education"],
        $parsedBody["career"],
        $parsedBody["email"],
        $parsedBody["address_first"],
        $parsedBody["address_second"],
        $parsedBody["address_third"]
      );

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

  public function getMapClassroom($request, $response, $args) {
    try {
      $parsedBody = $request->getParsedBody();

      $data = $this->teacher->getMapClassroom($this->datatables->filter($parsedBody));
      $pack = $this->datatables->get($data, $parsedBody);

      return $response->withJson($pack);
    } catch (\Exception $e) {
      return [];
    }
  }

  public function getMapStudent($request, $response, $args) {
    try {
      $parsedBody = $request->getParsedBody();

      $data = $this->teacher->getMapStudent($this->datatables->filter($parsedBody));
      $pack = $this->datatables->get($data, $parsedBody);

      return $response->withJson($pack);
    } catch (\Exception $e) {
      return [];
    }
  }

  public function createMap($request, $response, $args) {
    try {
      $parsedBody = $request->getParsedBody();
      
      $result = $this->teacher->createMap(
        $parsedBody["map_classroom"],
        $parsedBody["map_academicyear"],
        $parsedBody["map_teacher_id"]
      );

      return $response->withJson($result);
    } catch (Exception $e) {
      return [];
    }
  }

  public function getAcademicyear($request, $response, $args) {
    try {
      return $response->withJson($this->teacher->getAcademicyear());
    } catch (\Exception $e) {
      return [];
    }
  }

  public function mapDelete($request, $response, $args) {
    $parsedBody = $request->getParsedBody();

    $result = $this->teacher->mapDelete(
      $parsedBody['id']
    );

    return $response->withJson($result);
  }

}

