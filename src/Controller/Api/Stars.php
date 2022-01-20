<?php

namespace App\Controller\Api;

use App\Controller\Controller;

class Stars extends Controller
{
    public function __invoke()
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin : *', false);
        return $this->index();
    }

    public function index()
    {
        $json = [];
        if (isset($_POST['note'], $_POST['recipe_id'], $_SERVER['REMOTE_ADDR'])) {
            $note = $_POST['note'];

            if ($note > 0 && $note <= 5) {
                $req = $this->pdo->prepare('SELECT id FROM stars WHERE ip_address = :ip AND recipe_id = :id');
                $req->bindParam('ip', $_SERVER['REMOTE_ADDR'], \PDO::PARAM_STR);
                $req->bindParam('id', $_POST['recipe_id'], \PDO::PARAM_INT);
                $success = $req->execute();

                if ($success && $req->rowCount() === 0) {
                    try {
                        $req = $this->pdo->prepare('INSERT INTO stars (ip_address, recipe_id, note) VALUES (:ip, :id, :note)');
                        $req->bindParam('ip', $_SERVER['REMOTE_ADDR'], \PDO::PARAM_STR);
                        $req->bindParam('id', $_POST['recipe_id'], \PDO::PARAM_INT);
                        $req->bindParam('note', $note, \PDO::PARAM_INT);
                        $success = $req->execute();
                        $json['success']['req'] = $success;
                        $json['success']['msg'] = 'Vous avez ajouté la note de ' . (int)$note . ' à la recette !';

                        if ($success) {
                            $req = $this->pdo->prepare('SELECT CEIL(AVG(note)) note FROM stars WHERE recipe_id = :id GROUP BY recipe_id');
                            $req->bindParam('id', $_POST['recipe_id'], \PDO::PARAM_INT);
                            $req->execute();
                            $json['success']['note'] = $req->fetch(\PDO::FETCH_OBJ)->note;
                        }
                    } catch(\PDOException $e) {
                        if ($e->getCode() === '23000') {
                            $json['error']['msg'] = (int)$_POST['recipe_id'] . ' n\'est pas un identifiant valide';
                        } else {
                            $json['error']['msg'] = 'Une erreur est survenue : ' . $e->getCode();
                            // $json['error']['msg'] = $e->getMessage();
                        }
                    }
                } else {
                    $json['error']['recipe'] = $_POST['recipe_id'];
                    $json['error']['req'] = $success;
                    $json['error']['count'] = $req->rowCount();
                    $json['error']['msg'] = 'Impossible d\'ajouter une note';
                }
            } else {
                $json['error'] = 'Note invalide';
            }

        } else {
            $json['error'] = 'Données incomplètes';
        }

        return json_encode($json);
    }
}