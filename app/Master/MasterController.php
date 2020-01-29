<?php

namespace App\Master;

use App\Master\MasterAPI;
use App\Master\PrefixTable;
use App\Common\View;
use App\Common\Datatables;

class MasterController
{
  public function __construct() {
    $this->master = new MasterAPI;
    $this->view = new View;
    $this->prefix_table = new PrefixTable;
    $this->career_table = new CareerTable;
    $this->datatables = new Datatables;
  }

  public function ViewNamePrefix($request, $response, $args) {
    return $this->view->render('pages/master/name_prefix');
  }

  public function all($request, $response, $args) {
    $parsedBody = $request->getParsedBody();

    $data = $this->master->all($this->datatables->filter($parsedBody));
    $pack = $this->datatables->get($data, $parsedBody);

    return $response->withJson($pack);
  }

  public function update($request, $response, $args) {
    $parsedBody = $request->getParsedBody();

      $result = $this->master->update(
        $this->prefix_table->field[$parsedBody['name']],
        $parsedBody['pk'],
        $parsedBody['value'],
        $this->prefix_table->table
      );

      return $response->withJson($result);
  }

  public function create($request, $response, $args) {
    $parsedBody = $request->getParsedBody();

    if ( trim($parsedBody['name_prefix']) === '') {
      return $response->withJson($this->message->result(false, 'Data must not be blank!'));
    }

    $result = $this->master->create(
      $parsedBody['name_prefix']
    );
    return $response->withJson($result);
  }

  public function delete($request, $response, $args) {
    $parsedBody = $request->getParsedBody();

    $result = $this->master->delete(
      $parsedBody['id']
    );

    return $response->withJson($result);
  }

  public function ViewCareer($request, $response, $args) {
    return $this->view->render('pages/master/career');
  }

  public function careerAll($request, $response, $args) {
    $parsedBody = $request->getParsedBody();

    $data = $this->master->careerAll($this->datatables->filter($parsedBody));
    $pack = $this->datatables->get($data, $parsedBody);

    return $response->withJson($pack);
  }

  public function careerUpdate($request, $response, $args) {
    $parsedBody = $request->getParsedBody();

      $result = $this->master->update(
        $this->career_table->field[$parsedBody['name']],
        $parsedBody['pk'],
        $parsedBody['value'],
        $this->career_table->table
      );

      return $response->withJson($result);
  }

  public function careerCreate($request, $response, $args) {
    $parsedBody = $request->getParsedBody();

    if ( trim($parsedBody['name_career']) === '') {
      return $response->withJson($this->message->result(false, 'Data must not be blank!'));
    }

    $result = $this->master->careerCreate(
      $parsedBody['name_career']
    );
    return $response->withJson($result);
  }

  public function careerDelete($request, $response, $args) {
    $parsedBody = $request->getParsedBody();

    $result = $this->master->careerDelete(
      $parsedBody['id']
    );

    return $response->withJson($result);
  }

}

