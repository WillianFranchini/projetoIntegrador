<?php

class Estudante implements IBaseModelo{

        // vars -------------------------------------
        private $matricula;
        private $nome;
        private $curso;
        private $email;
        private $status;

        private $conn;
        private $stmt;
        // ------------------------------------------
        // gets -------------------------------------
        public function getMatricula() {
                return $this->matricula;
        }

        public function getNome() {
                return $this->nome;
        }

        public function getCurso() {
                return $this->curso;
        }

        public function getEmail() {
                return $this->email;
        }

        public function getStatus() {
                return $this->status;
        }
        // ------------------------------------------
        // sets -------------------------------------
        public function setMatricula($matricula) {
                $this->matricula = $matricula;
        }

        public function setNome($nome) {
                $this->nome = $nome;
        }

        public function setCurso($curso) {
                $this->curso = $curso;
        }

        public function setEmail($email) {
                $this->email = $email;
        }

        public function setStatus($status) {
                $this->status = $status;
        }
        // ------------------------------------------
        public function __construct() {
                //Cria conexão com o banco
                $this->conn = Database::conectar();
        }

        public function __destruct(){
                //Fecha a conexão
                Database::desconectar();
        }
        // ------------------------------------------

        public function inserir(){
                try{
                        //Comando SQL para inserir um estudante
                        $query="INSERT INTO Estudante 
                                VALUES (:matricula, :nome, :curso, :email, :status) ";

                        $this->stmt= $this->conn->prepare($query);

                        $this->stmt->bindValue(':matricula', $this->matricula, PDO::PARAM_STR);
                        $this->stmt->bindValue(':nome', $this->nome, PDO::PARAM_STR);
                        $this->stmt->bindValue(':curso', $this->curso, PDO::PARAM_STR);
                        $this->stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
                        $this->stmt->bindValue(':status', $this->status, PDO::PARAM_STR);

                        if($this->stmt->execute()){
                                return true;
                        }        
                } catch(PDOException $e) {
                        echo "<div class='alert alert-danger'>".$e->getMessage()."</div>";      
                        return false;
                }
        }

        public function alterar(){
                try{

                        //Comando SQL para inserir um estudante
                        $query="UPDATE Estudante 
                                SET nome = :nome, 
                                curso = :curso, 
                                email = :email, 
                                status = :status 
                                WHERE matricula=:matricula ";
                        $this->stmt= $this->conn->prepare($query);

                        $this->stmt->bindValue(':matricula', $this->nome, PDO::PARAM_STR);
                        $this->stmt->bindValue(':nome', $this->nome, PDO::PARAM_STR);
                        $this->stmt->bindValue(':curso', $this->curso, PDO::PARAM_STR);
                        $this->stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
                        $this->stmt->bindValue(':status', $this->nome, PDO::PARAM_STR);


                        if($this->stmt->execute()){
                                return true;
                        }        
                } catch(PDOException $e) {
                        echo "<div class='alert alert-danger'>".$e->getMessage()."</div>";      
                        return false;
                }
        }

        public function excluir(){
                try{
                        //Comando SQL para inserir um estudante
                        $query="DELETE FROM Estudante 
                                WHERE matricula=:matricula ";
                        $this->stmt= $this->conn->prepare($query);
                        $this->stmt->bindValue(':matricula', $this->matricula, PDO::PARAM_STR);
                        if($this->stmt->execute()){
                                return true;
                        }        
                } catch(PDOException $e) {
                        echo "<div class='alert alert-danger'>".$e->getMessage()."</div>";      
                        return false;
                }
        }

        public function listarTodos($nome=null){

                try{
                        $estudantes = array();

                        //Comando SQL para inserir um estudante
                        if(!is_null($nome)){
                                //Pesquisa pelo nome
                                $query="SELECT matricula,nome,curso,email,status FROM Estudante WHERE nome LIKE :nome";
                        }else{
                                // Pesquisa todos
                                $query="SELECT matricula,nome,curso,email,status FROM Estudante";
                        }
                        $this->stmt= $this->conn->prepare($query);
                        if(!is_null($nome))$this->stmt->bindValue(':nome', '%'.$nome.'%', PDO::PARAM_STR);

                        if($this->stmt->execute()){
                                // Associa cada registro a uma classe Estudante
                                // Depois, coloca os resultados em um array
                                $estudantes = $this->stmt->fetchAll(PDO::FETCH_CLASS,"Estudante");  

                        }

                        return $estudantes;            
                } catch(PDOException $e) {
                        echo "<div class='alert alert-danger'>".$e->getMessage()."</div>";   
                        return null;
                }

        }

        public function listarUnico($matricula){

                try{
                        $query="SELECT matricula,nome,curso,email,status FROM Estudante WHERE matricula=:matricula";
                        $this->stmt= $this->conn->prepare($query);
                        $this->stmt->bindValue(':matricula', $matricula, PDO::PARAM_STR);

                        if($this->stmt->execute()){
                                // Associa o registro a uma classe Estudante
                                $estudante = $this->stmt->fetchAll(PDO::FETCH_CLASS,"Estudante");  

                        }

                        return $estudante[0];            
                } catch(PDOException $e) {
                        echo "<div class='alert alert-danger'>".$e->getMessage()."</div>";   
                        return null;
                }

        }

        public function printTodos($estudantes)
        {
                if(!empty($estudantes)){
                        foreach ($estudantes as $est) {
                                echo "<tr>
                                        <td>".$est->getMatricula()."</td>
                                        <td>".$est->getNome()."</td>
                                        <td>".$est->getCurso()."</td>
                                        <td>".$est->getEmail()."</td>
                                        <td>".$est->getStatus()."</td>
                                        " ;  
                                echo '
                              <td>
                            <!-- Alterar -->
                            <button type="button" class="btn btn-warning text-light" data-toggle="modal" data-target="#exampleModalCenter">
                            <i class="fas fa-edit"></i>
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                               <div class="modal-dialog modal-dialog-centered" role="document">
                                  <div class="modal-content">
                                     <button type="button" class="close ml-auto" data-dismiss="modal" aria-label="Close">X &nbsp; </button>
                  '.
                                        //include_once "formCadAluno.php";
                              '.
                                  </div>
                               </div>
                            </div>
                            <!-- Deletar -->
                            <button type="button" class="btn btn-danger text-light" data-toggle="modal" data-target="#cpp2">
                            <i class="fas fa-trash-alt"></i>
                            </button>
                            <!-- Modal -->
                            <div class="modal fade" id="cpp2" tabindex="-1" role="dialog" aria-labelledby="cpp2" aria-hidden="true">
                               <div class="modal-dialog modal-dialog-centered" role="document">
                                  <div class="modal-content">
                                     <div class=\'modal-body\'>
                                        <p class=\'text-dark\'>Deseja realmente excluir?</p>
                                     </div>
                                     <div class=\'modal-footer\'>
                                        <a href=\'listcrianca.php?id={$registro[\' id \']}\' type=\'button\' class=\'btn btn-success\' id=\'delete\'>Confirmar</a>
                                        <button type=\'button\' data-dismiss=\'modal\' class=\'btn btn-danger\'>Cancelar</button>
                                     </div>
                                  </div>
                               </div>
                            </div>
                            <!-- Mostrar todos -->
                            <a href=\'listEmprestimos.php?id={$registro[\' id \']}\' class="btn btn-info text-light">
                            <i class="fas fa-clipboard-list"></i>
                            </a>
                            <!-- -->
                         </td>
                       </tr>
                                  ';
                        }
                }
        }
}
