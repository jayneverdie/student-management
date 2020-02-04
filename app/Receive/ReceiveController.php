<?php

namespace App\Receive;

use App\Receive\ReceiveAPI;
use App\Common\View;
use App\Common\Datatables;
use App\Common\Tool;

class ReceiveController
{
  public function __construct() {
    $this->receive = new ReceiveAPI;
    $this->view = new View;
    $this->datatables = new Datatables;
    $this->tool = new Tool;
  }

  public function ViewReceive($request, $response, $args) {
    return $this->view->render('pages/receive/receive');
  }

  public function all($request, $response, $args) {
    $parsedBody = $request->getParsedBody();
    $params = $request->getQueryParams();
    $dateview = $params["dateview"];
    $dateview = date('Y-m-d', strtotime($dateview));

    $data = $this->receive->all($this->datatables->filter($parsedBody),$dateview);
    $pack = $this->datatables->get($data, $parsedBody);

    return $response->withJson($pack);
  }

  public function getStudentByParent($request, $response, $args) {
    try {
      $params = $request->getQueryParams();

      return $response->withJson($this->receive->getStudentByParent($params["card_id"]));
    } catch (\Exception $e) {
      return [];
    }
  }

  public function getStudentById($request, $response, $args) {
    try {
      $params = $request->getQueryParams();

      return $response->withJson($this->receive->getStudentById($params["id"]));
    } catch (\Exception $e) {
      return [];
    }
  }

  public function getRelationByStudent($request, $response, $args) {
    try {
      $params = $request->getQueryParams();

      return $response->withJson($this->receive->getRelationByStudent($params["student_id"],$params["parent_id"]));
    } catch (\Exception $e) {
      return [];
    }
  }

  public function getHours($request, $response, $args) {
    try {
      return $response->withJson($this->receive->getHours());
    } catch (\Exception $e) {
      return [];
    }
  }

  public function getMinutes($request, $response, $args) {
    try {
      return $response->withJson($this->receive->getMinutes());
    } catch (\Exception $e) {
      return [];
    }
  }

  public function Send($request, $response, $args) {
    try {
      $parsedBody = $request->getParsedBody();

      if ($parsedBody["form_type"]==="send") {
        $result = $this->receive->Send(
          $parsedBody["send_student_id"],
          $parsedBody["send_parent_id"],
          $parsedBody["send_time"],
          $parsedBody["send_time_hour"],
          $parsedBody["send_time_minute"]
        );
      }else{
        $result = $this->receive->Receive(
          $parsedBody["send_student_id"],
          $parsedBody["send_parent_id"],
          $parsedBody["send_time"],
          $parsedBody["send_time_hour"],
          $parsedBody["send_time_minute"]
        );
      }
      
      return $response->withJson($result);
    } catch (\Exception $e) {
      return [];
    }
  }

  public function Read($request, $response, $args) {
    try {
      $params = $request->getQueryParams();
      return $response->withJson($this->receive->Read($params["card_id"]));
    } catch (\Exception $e) {
      return [];
    }
  }

  public function Delete($request, $response, $args) {
    try {
      $parsedBody = $request->getParsedBody();
      
      $result = $this->receive->Delete(
        $parsedBody["student_id"],
        $parsedBody["dateview"]
      );
      
      return $response->withJson($result);
    } catch (\Exception $e) {
      return [];
    }
  }

}

