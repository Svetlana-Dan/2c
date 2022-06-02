<?php
include_once 'database.php';
include_once 'fun.php';
class Aut
{
  
  
  protected $data = null;

  protected array $data_errors;
  
  const SEPARATOR = '||';
  public ?int $id;
  public ?string $ip;

  public ?string $date = '';
  public ?int $duration = 10;

  public bool $if_done = false;
  public ?string $status = '';
  protected static string $datafile = 'data/data.txt';
	public ?string $type ='текущие';
	


  protected array $errors = [];
  
  public ?string $login = '';
  public ?string $password = '';
  
  
 	public function __construct(array $data = [])
  {
   	$this->adminAuthorization($data);

  }
  
public function saveReg()
  {
    //$pdo = new PDO("mysql:host=127.0.0.1;dbname=test_3", "root", "user2020");
    $sql = Database::prep('INSERT INTO users (`login`, `password`, `created_at`) VALUES (:login, :password, NOW());');
    $sql->execute([
      'login'=>$this->login,
      'password'=>$this->password,
    ]);
    
    //$pdo->prepare('INSERT INTO apps (`ip`, `name`, `lastname`, `email`, `phone`, `topic`, `pay`, `confirm`, `created_at`) VALUES (\''.this->id.'\',\''.this->name.'\',\''.this->lastname.'\',\''.this->email.'\',\''.this->phone.'\',\''.this->topic_id.'\',\''.this->pay_id.'\',\''.this->confirm.'\',\''.this->created_at.'\')');
    //$this->ensureDataDir();
    //file_put_contents(static::$datafile, $this->toString(), FILE_APPEND);
  }

  public function adminAuthorization(array $data = [])
    {
        if ($data)
        {        
            $this->login = $data['login'] ?? '';
            $this->password = $data['password'] ?? '';
        } 
    }
    public function validateAdmin() : bool{
        $this -> errors = [];   
        if (!$this -> login)
        {
            $this -> errors[]='Логин не заполнен';
        }
        if (!$this -> password)
        {
            $this -> errors[]= 'Пароль не заполнен';
        }
        if ($this -> login && $this -> password)
        {
            $check = $this->correctAdmin($this->login, $this->password);
            if ( $check == 0)
            {
                $this -> errors[]='Неверный логин или пароль';
            }
        }
        return ! $this->hasErrorsAdmin();
    }
  
  	public function validateReg() : bool{
        $this -> errors = [];   
        if (!$this -> login)
        {
            $this -> errors[]='Логин не заполнен';
        }
        if (!$this -> password)
        {
            $this -> errors[]= 'Пароль не заполнен';
        }
        if ($this -> login && $this -> password)
        {
            $check = $this->correctAdminReg($this->login, $this->password);
            if ( $check !== 0)
            {
                $this -> errors[]='Аккаунт уже существует';
            }
        }
        return ! $this->hasErrorsAdmin();
    }

    public function hasErrorsAdmin() : bool
    {
        return ! empty($this->errors);
    }
    
    public function getErrorsAdmin(): array
    {
        return $this->errors; 
    }

    public function correctAdmin($login,$password)
    {
        $result = [];
        $admins = Database::query("SELECT COUNT(*) FROM users WHERE `login` = '" . $login . "' AND `password` = '" . $password . "';")->fetch(PDO::FETCH_ASSOC);
        $result = $admins['COUNT(*)'];
        return $result;
    }

  public function correctAdminReg($login,$password)
    {
        $result = [];
        $admins = Database::query("SELECT COUNT(*) FROM users WHERE `login` = '" . $login . "';")->fetch(PDO::FETCH_ASSOC);
        $result = $admins['COUNT(*)'];
        return $result;
    }

}