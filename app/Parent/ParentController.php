<?php

namespace App\Parent;

use App\Parent\ParentAPI;
use App\Common\View;
use App\Common\Datatables;
use App\Common\Tool;
use App\Master\MapParentStudentTable;
use App\Parent\ParentTable;

class ParentController
{
  public function __construct() {
    $this->parent = new ParentAPI;
    $this->view = new View;
    $this->datatables = new Datatables;
    $this->tool = new Tool;
    $this->map_table = new MapParentStudentTable;
    $this->parent_table = new ParentTable;
  }

  public function ViewParent($request, $response, $args) {
    return $this->view->render('pages/parent/parent');
  }

  public function all($request, $response, $args) {
    $parsedBody = $request->getParsedBody();

    $data = $this->parent->all($this->datatables->filter($parsedBody));
    $pack = $this->datatables->get($data, $parsedBody);

    return $response->withJson($pack);
  }

  public function getNameFix($request, $response, $args) {
    try {
      return $response->withJson($this->parent->getNameFix());
    } catch (\Exception $e) {
      return [];
    }
  }

  public function getSex($request, $response, $args) {
    try {
      return $response->withJson($this->parent->getSex());
    } catch (\Exception $e) {
      return [];
    }
  }

  public function getEducation($request, $response, $args) {
    try {
      return $response->withJson($this->parent->getEducation());
    } catch (\Exception $e) {
      return [];
    }
  }

  public function getCareer($request, $response, $args) {
    try {
      return $response->withJson($this->parent->getCareer());
    } catch (\Exception $e) {
      return [];
    }
  }

  public function update($request, $response, $args) {
    $parsedBody = $request->getParsedBody();

      $result = $this->parent->update(
        $this->parent_table->field[$parsedBody['name']],
        $parsedBody['pk'],
        $parsedBody['value'],
        $this->parent_table->table
      );

      return $response->withJson($result);
  }

  public function create($request, $response, $args) {
    try {
      $parsedBody = $request->getParsedBody();
      $root = 'files/document/parent/';
      $rootimg = 'files/images/parent/';
      
      if (isset($_FILES["files_upload"]["name"])) {
        $this->tool->initFolder($root, $parsedBody['card_id']);
        
        for( $i=0; $i < count($_FILES["files_upload"]["name"] ); $i++ ){
          $sur = $_FILES['files_upload']['name'][$i];
          $typename = strrchr($sur,".");
          $newfilename = "parent_".$parsedBody["card_id"]."_".date('dmY_His')."_".$i.$typename;
          move_uploaded_file($_FILES["files_upload"]["tmp_name"][$i],iconv('UTF-8','windows-874',$root.$parsedBody['card_id'].'/'.$newfilename));
          $result = $this->parent->logsfile(
            $newfilename,'parent',$parsedBody['card_id']
          );
        }
      }

      if (isset($_FILES["image_parent"]["name"])) {
        $this->tool->initFolder($rootimg, $parsedBody['card_id']);
        $sur = $_FILES['image_parent']['name'];
        $typename = strrchr($sur,".");
        move_uploaded_file($_FILES["image_parent"]["tmp_name"],iconv('UTF-8','windows-874',$rootimg.$parsedBody['card_id'].'/'.$parsedBody['card_id'].$typename));
      }

      $result = $this->parent->create(
        $parsedBody["card_id"],
        $parsedBody["name_prefix"],
        $parsedBody["parent_name"],
        $parsedBody["parent_lastname"],
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

  public function loadFile($request, $response, $args) {
    try {
      $params = $request->getQueryParams();
      return $response->withJson($this->parent->loadFile($params["card_id"],$params["filetype"]));
    } catch (\Exception $e) {
      return [];
    }
  }

  public function getMapRelation($request, $response, $args) {
    try {
      $parsedBody = $request->getParsedBody();
      $params = $request->getQueryParams();
      $data = $this->parent->getMapRelation($params["parent_id"]);
      $pack = $this->datatables->get($data, $parsedBody);

      return $response->withJson($pack);
    } catch (\Exception $e) {
      return [];
    }
  }

  public function getRelation($request, $response, $args) {
    try {
      return $response->withJson($this->parent->getRelation());
    } catch (\Exception $e) {
      return [];
    }
  }

  public function createMap($request, $response, $args) {
    try {
      $parsedBody = $request->getParsedBody();
      
      $result = $this->parent->createMap(
        $parsedBody["map_relation"],
        $parsedBody["map_student_id"],
        $parsedBody["map_parent_id"],
        $parsedBody["map_remark"]
      );

      return $response->withJson($result);
    } catch (Exception $e) {
      return [];
    }
  } 

  public function mapUpdate($request, $response, $args) {
    $parsedBody = $request->getParsedBody();
      $result = $this->parent->mapUpdate(
        $parsedBody['name'],
        $parsedBody['pk'],
        $parsedBody['value'],
        $this->map_table->table
      );

      return $response->withJson($result);
  }

  public function mapDelete($request, $response, $args) {
    $parsedBody = $request->getParsedBody();

    $result = $this->parent->mapDelete(
      $parsedBody['id']
    );

    return $response->withJson($result);
  }

  public function changeImg($request, $response, $args) {
    try {
      $parsedBody = $request->getParsedBody();
      // print_r($parsedBody);
      // echo $_FILES["img_change"]["name"];
      // exit();
      $rootimg = 'files/images/parent/';

      if (isset($_FILES["img_change"]["name"])) {
        $this->tool->initFolder($rootimg, $parsedBody['parent_id']);
        $sur = $_FILES['img_change']['name'];
        $typename = strrchr($sur,".");
        move_uploaded_file($_FILES["img_change"]["tmp_name"],iconv('UTF-8','windows-874',$rootimg.$parsedBody['parent_id'].'/'.$parsedBody['parent_id'].$typename));
      }

      return json_encode(['result'=>true,'card_id'=>$parsedBody['parent_id']]);
    } catch (\Exception $e) {
      return [];
    }
  }

}

