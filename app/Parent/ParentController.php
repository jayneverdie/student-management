<?php

namespace App\Parent;

use App\Parent\ParentAPI;
use App\Common\View;
use App\Common\Datatables;
use App\Common\Tool;

class ParentController
{
  public function __construct() {
    $this->parent = new ParentAPI;
    $this->view = new View;
    $this->datatables = new Datatables;
    $this->tool = new Tool;
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

  public function create($request, $response, $args) {
    try {
      $parsedBody = $request->getParsedBody();
      $root = 'files/document/parent/';
      
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

}

