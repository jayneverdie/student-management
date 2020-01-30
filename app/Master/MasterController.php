<?php

namespace App\Master;

use App\Master\MasterAPI;
use App\Master\PrefixTable;
use App\Master\RelationTable;
use App\Master\EducationTable;
use App\Common\View;
use App\Common\Datatables;

class MasterController
{
  public function __construct() {
    $this->master = new MasterAPI;
    $this->view = new View;
    $this->prefix_table = new PrefixTable;
    $this->career_table = new CareerTable;
    $this->relation_table = new RelationTable;
    $this->education_table = new EducationTable;
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

  // Career
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

  // Relation
  public function ViewRelation($request, $response, $args) {
    return $this->view->render('pages/master/relation');
  }

  public function relationAll($request, $response, $args) {
    $parsedBody = $request->getParsedBody();

    $data = $this->master->relationAll($this->datatables->filter($parsedBody));
    $pack = $this->datatables->get($data, $parsedBody);

    return $response->withJson($pack);
  }

  public function relationUpdate($request, $response, $args) {
    $parsedBody = $request->getParsedBody();

      $result = $this->master->update(
        $this->relation_table->field[$parsedBody['name']],
        $parsedBody['pk'],
        $parsedBody['value'],
        $this->relation_table->table
      );

      return $response->withJson($result);
  }

  public function relationCreate($request, $response, $args) {
    $parsedBody = $request->getParsedBody();

    if ( trim($parsedBody['name_relation']) === '') {
      return $response->withJson($this->message->result(false, 'Data must not be blank!'));
    }

    $result = $this->master->relationCreate(
      $parsedBody['name_relation']
    );
    return $response->withJson($result);
  }

  public function relationDelete($request, $response, $args) {
    $parsedBody = $request->getParsedBody();

    $result = $this->master->relationDelete(
      $parsedBody['id']
    );

    return $response->withJson($result);
  }

  // Education
  public function ViewEducation($request, $response, $args) {
    return $this->view->render('pages/master/education');
  }

  public function educationAll($request, $response, $args) {
    $parsedBody = $request->getParsedBody();

    $data = $this->master->educationAll($this->datatables->filter($parsedBody));
    $pack = $this->datatables->get($data, $parsedBody);

    return $response->withJson($pack);
  }

  public function educationUpdate($request, $response, $args) {
    $parsedBody = $request->getParsedBody();

      $result = $this->master->update(
        $this->education_table->field[$parsedBody['name']],
        $parsedBody['pk'],
        $parsedBody['value'],
        $this->education_table->table
      );

      return $response->withJson($result);
  }

  public function educationCreate($request, $response, $args) {
    $parsedBody = $request->getParsedBody();

    if ( trim($parsedBody['name_education']) === '') {
      return $response->withJson($this->message->result(false, 'Data must not be blank!'));
    }

    $result = $this->master->educationCreate(
      $parsedBody['name_education']
    );
    return $response->withJson($result);
  }

  public function educationDelete($request, $response, $args) {
    $parsedBody = $request->getParsedBody();

    $result = $this->master->educationDelete(
      $parsedBody['id']
    );

    return $response->withJson($result);
  }

}

