<?php


namespace MyApp\Controllers;


use MyApp\App;
use MyApp\Models\Table;

class IndexController extends Controller
{
    public function actionIndex()
    {
        $this->renderTemplate("index.twig", [
            "table" => Table::getAll(),
            "headers" => Table::getHeaders(),
        ]);
    }

    public function actionAdd_column()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            Table::addColumn($data['name']);
            echo "OK";
        } catch (\PDOException $Exception) {
            http_response_code(501);
            echo "Error:" . $Exception->getMessage();
        }
    }

    public function actionDeleteColumn()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            Table::deleteColumn($data['name']);
            echo "OK";
        } catch (\PDOException $Exception) {
            http_response_code(501);
            echo "Error:" . $Exception->getMessage();
        }
    }

    public function actionEditColumn()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            Table::editColumn($data['oldName'], $data['name']);
            echo "OK";
        } catch (\PDOException $Exception) {
            http_response_code(501);
            echo "Error:" . $Exception->getMessage();
        }
    }

    public function actionDeleteTerm()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            Table::deleteTerm($data['name']);
            echo "OK";
        } catch (\PDOException $Exception) {
            http_response_code(501);
            echo "Error:" . $Exception->getMessage();
        }
    }

    public function actionMakeEditTerm()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            $term = Table::getTerm($data['id']);
            $this->renderTemplate("editTermDialogBox.twig", [
                "term" => $term,
//                "id" => $data['id']
            ]);
        } catch (\PDOException $Exception) {
            http_response_code(501);
            echo "Error:" . $Exception->getMessage();
        }
    }

    public function actionEditTerm()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            Table::editTerm($data['id'], $data['str']);
            echo "OK";
        } catch (\PDOException $Exception) {
            http_response_code(501);
            echo "Error:" . $Exception->getMessage();
        }
    }

    public function actionMakeAddTerm()
    {
        try {
//            $data = json_decode(file_get_contents('php://input'), true);
            $headers = Table::getHeaders();
            $this->renderTemplate("addTermDialogBox.twig", [
                "headers" => $headers
            ]);
        } catch (\PDOException $Exception) {
            http_response_code(501);
            echo "Error:" . $Exception->getMessage();
        }
    }

    public function actionAddTerm()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            Table::addTerm($data['names'], $data['values']);
            $arr = ['id' => App::instsnce()->getDB()->getLink()->lastInsertId()];
            echo json_encode($arr);
        } catch (\PDOException $Exception) {
            http_response_code(501);
            echo "Error:" . $Exception->getMessage();
        }
    }
}