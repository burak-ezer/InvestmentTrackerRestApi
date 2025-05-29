<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app = new \Slim\App;

$app->delete('/investment/{userID}/{id}', function (Request $request, Response $response) {

    $db = new Db();
    $userID = $request -> getAttribute("userID");
    $id = $request -> getAttribute("id");

    try {
        $db = $db->connect();
        $myInvestment = $db->query("DELETE FROM investment WHERE userID=$userID AND id=$id")->fetchAll(PDO::FETCH_OBJ);

        return $response
            ->withStatus(200)
            ->withHeader("Content-Type", 'application/json')
            ->withJson($myInvestment);
    } catch (PDOException $e) {
        return $response->withJson(
            array(
                "error" => array(
                    "text" => $e->getMessage(),
                    "code" => $e->getCode()
                )
            )
        );
    }
    $db = null;
});

$app->get('/investment/{userID}', function (Request $request, Response $response) {

    $db = new Db();
    $userID = $request -> getAttribute("userID");

    try {
        $db = $db->connect();
        $myInvestment = $db->query("SELECT * FROM investment WHERE userID=$userID")->fetchAll(PDO::FETCH_OBJ);

        return $response
            ->withStatus(200)
            ->withHeader("Content-Type", 'application/json')
            ->withJson($myInvestment);
    } catch (PDOException $e) {
        return $response->withJson(
            array(
                "error" => array(
                    "text" => $e->getMessage(),
                    "code" => $e->getCode()
                )
            )
        );
    }
    $db = null;
});

$app->post('/investment/add', function (Request $request, Response $response) {

    $db = new Db();

    $userID = $request->getParam("userID");
    $currencyType = $request->getParam("currencyType");
    $amount = $request->getParam("amount");
    $totalInvestmentTl = $request->getParam("totalInvestmentTl");
    $valueCurrency = $request->getParam("valueCurrency");

    try {
        $db = $db->connect();

        $statement = "INSERT INTO investment (userID,currencyType,amount,totalInvestmentTl,valueCurrency) VALUES(:userID,:currencyType,:amount,:totalInvestmentTl,:valueCurrency)";
        $prepare = $db->prepare($statement);

        $prepare->bindParam("userID", $userID);
        $prepare->bindParam("currencyType", $currencyType);
        $prepare->bindParam("amount", $amount);
        $prepare->bindParam("totalInvestmentTl", $totalInvestmentTl);
        $prepare->bindParam("valueCurrency", $valueCurrency);

        $investment = $prepare->execute();

        if ($investment) {
            return $response
                ->withStatus(200)
                ->withHeader("Content-Type", 'application/json')
                ->withJson(array(
                    "text" => "Yatırımınız Eklenmiştir."
                ));
        } else {
            return $response
                ->withStatus(500)
                ->withHeader("Content-Type", 'application/json')
                ->withJson(array(
                    "text" => "Yatırımınız Eklenemedi."
                ));
        }
    } catch (PDOException $e) {
        return $response->withJson(
            array(
                "error" => array(
                    "text" => $e->getMessage(),
                    "code" => $e->getCode()
                )
            )
        );
    }
    $db = null;
});
