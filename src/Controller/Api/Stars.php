<?php

namespace App\Controller\Api;

use App\Controller\Controller;

class Stars extends Controller
{
    public function __invoke()
    {
        header('Content-Type: application/json');
        // header('Access-Control-Allow-Origin : *', false); // only on dev
        $json = [];
        
        if (isset($_POST['note'], $_POST['recipe_id'], $_SERVER['REMOTE_ADDR'])) {
            $note = $_POST['note'];

            if ($note > 0 && $note <= 5) {
                $req = $this->pdo->prepare('SELECT id FROM stars WHERE ip_address = :ip AND recipe_id = :id');
                $req->bindParam('ip', $_SERVER['REMOTE_ADDR'], \PDO::PARAM_STR);
                $req->bindParam('id', $_POST['recipe_id'], \PDO::PARAM_INT);
                $success = $req->execute();

                try {
                    if ($success && $req->rowCount() === 0) {
                        $json = $this->add($_SERVER['REMOTE_ADDR'], (int)$_POST['recipe_id'], (int)$note);                  
                    } else if ($success && $req->rowCount() === 1) {
                        $json = $this->update($_SERVER['REMOTE_ADDR'], (int)$_POST['recipe_id'], (int)$note); 
                    } else {
                        $json['error'] = [
                            'req' => $success,
                            'count' => $req->rowCount(),
                            'recipe' => $_POST['recipe_id'],
                            'msg' => 'Impossible d\'ajouter une note'
                        ];
                    }

                    if ($json['success']['req']) {
                        $req = $this->pdo->prepare('SELECT CEIL(AVG(note)) note FROM stars WHERE recipe_id = :id GROUP BY recipe_id');
                        $req->bindParam('id', $_POST['recipe_id'], \PDO::PARAM_INT);
                        $req->execute();
                        $json['success']['note'] = $req->fetchColumn();
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
                $json['error']['msg'] = 'Note invalide';
            }
        } else {
            $json['error']['msg'] = 'Données incomplètes';
        }

        return json_encode($json);
    }

    private function add(string $ip, int $recipe, int $note): array
    {
        $json = [];

        $req = $this->pdo->prepare('INSERT INTO stars (ip_address, recipe_id, note) VALUES (:ip, :id, :note)');
        $req->bindParam('ip', $ip, \PDO::PARAM_STR);
        $req->bindParam('id', $recipe, \PDO::PARAM_INT);
        $req->bindParam('note', $note, \PDO::PARAM_INT);
        $success = $req->execute();
        $json['success'] = [
            'req' => $success,
            'msg' => 'Vous avez ajouté la note de ' . $note . ' à cette recette !'
        ];

        return $json;
    }

    private function update(string $ip, int $recipe, int $note): array
    {
        $json = [];
     
        $req = $this->pdo->prepare('UPDATE stars SET note = :note WHERE ip_address = :ip AND recipe_id = :id');
        $req->bindParam('note', $note, \PDO::PARAM_INT);
        $req->bindParam('ip', $ip, \PDO::PARAM_STR);
        $req->bindParam('id', $recipe, \PDO::PARAM_INT);
        $success = $req->execute();
        $json['success'] = [
            'req' => $success,
            'msg' => 'Vous avez modifié votre note par ' . $note . ' à cette recette !'
        ];
     
        return $json;
    }
}