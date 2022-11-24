<?php
class Tasks extends Config {

    public function login($email, $pass) {
        $db = parent::connect();
        parent::set_names();
        $sql = "SELECT id, name, admin, pass, team FROM user WHERE email = ?;";
        $sql = $db->prepare($sql);
        $sql->bindValue(1, $email);
        $sql->execute();
        $query = $sql->fetch();
        // Si encuentra al usuario y la contraseña coincide entonces retorna los datos
        if ($query && password_verify($pass, $query['pass'])) {
            $result['id'] = $query['id'];
            $result['name'] = $query['name'];
            $result['admin'] = $query['admin'];
            $result['team'] = $query['team'];
        } else {
            $result['id'] = 0;
        }
        return $result;
    }

    // Insertar un nuevo usuario
    public function addUser1($name, $email, $pass, $color, $admin, $photo) {
        $link = parent::connect();
        parent::set_names();
        // Encripta la contraseña recbidia y la manda a la BD
        $passencrypt = password_hash($pass, PASSWORD_DEFAULT);
        $sql = "CALL insertUser(?, ?, ?, ?, ?, ?);";
        $sql = $link->prepare($sql);
        $sql->bindValue(1, $name);
        $sql->bindValue(2, $email);
        $sql->bindValue(3, $passencrypt);
        $sql->bindValue(4, $color);
        $sql->bindValue(5, $admin);
        $sql->bindValue(6, $photo);
        $result['status'] = $sql->execute();
        return true;
    }

    // Obtiene sólo la vista de actividades permitidas ver para el usuario
    public function getAllowed($team) {
        $db = parent::connect();
        parent::set_names();
        $sql = "SELECT task.*, user.name FROM task JOIN user ON user.id = task.asignment WHERE user.team = ? ORDER BY start;";
        $sql = $db->prepare($sql);
        $sql->bindValue(1, $team);
        $sql->execute();
        $results = $sql->fetchAll(PDO::FETCH_OBJ);
        $array = [];
        foreach ($results as $r) {
            $array[] = [
                'id' => $r->id,
                'title' => $r->title,
                'start' => $r->start,
                'end' => $r->end,
                'notes' => $r->notes,
                'status' => $r->status,
                'userID' => $r->asignment,
                'userName' => $r->name
            ];
        }
        return $array;
    }

    // Obtiene sólo los usuarios a los que les puede asignar tareas
    public function getUsers($team) {
        $db = parent::connect();
        parent::set_names();
        $sql = "SELECT * FROM user WHERE team = ?;";
        $sql = $db->prepare($sql);
        $sql->bindValue(1, $team);
        $sql->execute();
        $results = $sql->fetchAll(PDO::FETCH_OBJ);
        $array = [];
        foreach ($results as $r) {
            $array[] = [
                'id' => $r->id,
                'name' => $r->name,
                'email' => $r->email,
                'pass' => $r->pass,
                'color' => $r->color,
                'admin' => $r->admin
            ];
        }
        return $array;
    }

    // Obtiene a todos los usuarios de la empresa
    public function getAllUsers() {
        $db = parent::connect();
        parent::set_names();
        $sql = "SELECT * FROM user;";
        $sql = $db->prepare($sql);
        $sql->execute();
        $results = $sql->fetchAll(PDO::FETCH_OBJ);
        $array = [];
        foreach ($results as $r) {
            $array[] = [
                'id' => $r->id,
                'name' => $r->name,
                'email' => $r->email,
                'pass' => $r->pass,
                'color' => $r->color,
                'admin' => $r->admin
            ];
        }
        return $array;
    }

    // Obtiene todas las tareas de la empresa
    public function getAll() {
        $db = parent::connect();
        parent::set_names();
        $sql = "SELECT task.*, user.name FROM task JOIN user ON task.asignment = user.id ORDER BY start;";
        $sql = $db->prepare($sql);
        $sql->execute();
        $results = $sql->fetchAll(PDO::FETCH_OBJ);
        $array = [];
        foreach ($results as $r) {
            $array[] = [
                'id' => $r->id,
                'title' => $r->title,
                'start' => $r->start,
                'end' => $r->end,
                'notes' => $r->notes,
                'status' => $r->status,
                'userID' => $r->asignment,
                'userName' => $r->name
            ];
        }
        return $array;
    }

    // Obtiene las tareas de un usuario
    public function getOne($id) {
        $link = parent::connect();
        parent::set_names();
        $sql = "SELECT * FROM task JOIN user ON task.asignment = user.id WHERE id = ?;";
        $sql = $link->prepare($sql);
        $sql->bindValue(1, $id);
        $sql->execute();
        $result = $sql->fetch(PDO::FETCH_OBJ);
        // Si el result está vacío retornamos un arreglo vacío por medio una condición ternaria
        $array = $result ? [
                'id' => $result->id,
                'title' => $result->title,
                'start' => $result->start,
                'end' => $result->end,
                'notes' => $result->notes,
                'status' => $result->status,
                'userID' => $result->asignment,
                'userName' => $result->name,
        ] : [];
        return $array;
    }

    // Inserta una nueva tarea
    public function addTask1($title, $start, $end, $asignment, $notes) {
        $link = parent::connect();
        parent::set_names();
        $sql = "INSERT INTO task(title, start, end, asignment, notes) VALUES(?, ?, ?, ?, ?);";
        $sql = $link->prepare($sql);
        $sql->bindValue(1, $title);
        $sql->bindValue(2, $start);
        $sql->bindValue(3, $end);
        $sql->bindValue(4, $asignment);
        $sql->bindValue(5, $notes);
        $resultado['estatus'] = $sql->execute();
        $lastInsertId = $link->lastInsertId();
        if ($lastInsertId != "0") {
            $resultado['id'] = (int)$lastInsertId;
        }
        return $resultado;
    }

    // Actualiza una tarea
    public function updateTask($title, $start, $end, $status, $asignment, $notes, $id) {
        $link = parent::connect();
        parent::set_names();
        $sql = "UPDATE task SET title = ?, start = ?, end = ?, status = ?, asignment = ?, notes = ? WHERE id = ?;";
        $sql = $link->prepare($sql);
        $sql->bindValue(1, $title);
        $sql->bindValue(2, $start);
        $sql->bindValue(3, $end);
        $sql->bindValue(4, $status);
        $sql->bindValue(5, $asignment);
        $sql->bindValue(6, $notes);
        $sql->bindValue(7, $id);
        return $sql->execute();
    }

    public function updateStatus($id, $status) {
        $link = parent::connect();
        parent::set_names();
        $sql = "UPDATE task SET status = ? WHERE id = ?;";
        $sql = $link->prepare($sql);
        $sql->bindValue(1, $status);
        $sql->bindValue(2, $id);
        return $sql->execute();
    }

    // Para borrar una tarea
    public function deleteTask($id) {
        $link = parent::connect();
        parent::set_names();
        $sql = "DELETE FROM task WHERE id = ?;";
        $sql = $link->prepare($sql);
        $sql->bindValue(1, $id);
        return $sql->execute();
    }

    public function deleteUser($id) {
        $link = parent::connect();
        parent::set_names();
        $sql = "CALL deleteUser(?);";
        $sql = $link->prepare($sql);
        $sql->bindValue(1, $id);
        $result['status'] = $sql->execute();
        return $result;
    }

    public function editUser($name, $email, $pass, $color, $admin, $id, $photo) {
        $link = parent::connect();
        parent::set_names();
        if ($photo == null) {
            $sql = "UPDATE user SET name = ?, email = ?, pass = ?, color = ?, admin = ? WHERE id = ?";
            $sql = $link->prepare($sql);
            $sql->bindValue(6, $id);
        } else {
            $sql = "UPDATE user SET name = ?, email = ?, pass = ?, color = ?, admin = ?, photo = ? WHERE id = ?";
            $sql = $link->prepare($sql);
            $sql->bindValue(6, $photo);
            $sql->bindValue(7, $id);
        }
        $sql->bindValue(1, $name);
        $sql->bindValue(2, $email);
        $sql->bindValue(3, $pass);
        $sql->bindValue(4, $color);
        $sql->bindValue(5, $admin);
        return $sql->execute();
    } 

    public function getTeams() {
        $link = parent::connect();
        parent::set_names();
        $sql = "SET SESSION group_concat_max_len = 1000000;";
        $link->exec($sql);
        $sql = "SELECT * FROM getTeams;";
        $sql = $link->prepare($sql);
        $sql->execute();
        $result = $sql->fetchAll(PDO::FETCH_OBJ);
        $json = array();
        foreach ($result as $data) {
            // Sólo ingresa los usuarios si el equipo tiene miembros
            $usersData = array();
            if ($data->users) {
                $users = explode('|', $data->users);
                foreach ($users as $u) {
                    $user = explode(',', $u, 4);
                    array_push($usersData, ['id' => $user[0], 'name' => $user[1], 'email' => $user[2], 'photo' => $user[3]]);
                }
            }
            $object = [
                'team' => $data->team,
                'tName' => $data->tName,
                'users' => $usersData
            ];
            array_push($json, $object);
        }
        return $json;
    }

    public function getTeam($idTeam) {
        $link = parent::connect();
        parent::set_names();
        $sql = "SET SESSION group_concat_max_len = 1000000;";
        $link->exec($sql);
        $sql = "SELECT * FROM getTeams WHERE team = ?;";
        $sql = $link->prepare($sql);
        $sql->bindValue(1, $idTeam);
        $sql->execute();
        $result = $sql->fetchAll(PDO::FETCH_OBJ);
        $json = array();
        foreach ($result as $data) {
            // Sólo ingresa los usuarios si el equipo tiene miembros
            $usersData = array();
            if ($data->users) {
                $users = explode('|', $data->users);
                foreach ($users as $u) {
                    $user = explode(',', $u, 4);
                    array_push($usersData, ['id' => $user[0], 'name' => $user[1], 'email' => $user[2], 'photo' => $user[3]]);
                }
            }
            $object = [
                'team' => $data->team,
                'tName' => $data->tName,
                'users' => $usersData
            ];
            array_push($json, $object);
        }
        return $json;
    }

    public function getUnasigned() {
        $link = parent::connect();
        parent::set_names();
        $sql = "SELECT * FROM getUnasigned;";
        $sql = $link->prepare($sql);
        $sql->execute();
        $result = $sql->fetchAll(PDO::FETCH_OBJ);
        $json = array();
        foreach ($result as $data) {
            $usersData = array();
            $users = explode('|', $data->users);
            foreach ($users as $u) {
                $user = explode(',', $u, 4);
                array_push($usersData, ['id' => $user[0], 'name' => $user[1], 'email' => $user[2], 'photo' => $user[3]]);
            }
            $object = [
                'team' => '',
                'tName' => '',
                'users' => $usersData
            ];
            array_push($json, $object);
        }
        return $json;
    }

    public function addTeam($name) {
        $link = parent::connect();
        parent::set_names();
        $sql = "INSERT INTO team(name) VALUES(?);";
        $sql = $link->prepare($sql);
        $sql->bindValue(1, $name);
        return $sql->execute();
    }

    public function addToTeam($idTeam, $idUser) {
        $link = parent::connect();
        parent::set_names();
        $sql = "CALL addToTeam(?,?);";
        $sql = $link->prepare($sql);
        $sql->bindValue(1, $idTeam);
        $sql->bindValue(2, $idUser);
        return $sql->execute();
    }

    public function deleteTeam($team) {
        $link = parent::connect();
        parent::set_names();
        $sql = "DELETE FROM team WHERE id = ?;";
        $sql = $link->prepare($sql);
        $sql->bindValue(1, $team);
        return $sql->execute();
    }
}
