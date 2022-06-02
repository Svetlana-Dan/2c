<?php
include_once 'database.php';
include_once 'fun.php';

class App
{
  
  
  protected $data = null;

  protected array $data_errors;
  
  const SEPARATOR = '||';
  public ?int $id;
  public ?string $ip;
  //public ?int $date;
  public ?string $name = '';
  public ?string $place = '';
  public ?string $date = '';
  public ?int $duration = 10;
  public ?string $comment = '';
  //public ?string $topic = '';
  public bool $if_done = false;
  public ?string $status = '';
  protected static string $datafile = 'data/data.txt';
	public ?string $type ='текущие';
	

	//public static $topics = [
  //	1 => 'встреча',
  //	2 => 'звонок',
  //	3 => 'совещание',
  //  3 => 'дело',
	//];
  

  protected array $errors = [];
  
  public ?string $login = '';
  public ?string $password = '';
  
  
 	public function __construct(array $topics, array $durations, array $_data = [])
  {
   	
   	$this->data_errors = [];
    $this->data = array(
      ':name' => $_data['name'] ?? '',
      ':place' => $_data['place'] ?? '',
      ':date' => $_data['date'] ?? '',
      ':duration' => convertIndex($_data['duration'], $durations) ?? null,
      ':topic' =>  convertIndex($_data['topic'], $topics),
      ':comment' => $_data['comment'] ?? ''
    );
    //$this->id = uniqid();
    //$this->ip = getenv('REMOTE_ADDR');
    //$this->date = time();
    //$this->fill($data);
    //$this->status = '';
  }
  
  //public function fill(array $_data, array $topics, array $durations)
  //{
    //$this->data_errors = [];
    //$this->data = array(
      //':name' => $_data['name'] ?? '',
      //':place' => $_data['place'] ?? '',
      //':date' => $_data['date'] ?? '',
      //':duration' => convert_to_index($_data['duration'], $durations),
      //':topic' =>  convert_to_index($_data['topic'], $topics),
      //':comment' => $_data['comment'] ?? ''
    //);
      //$this->name = $data['name'] ?? '';
      //$this->place = $data['place'] ?? '';
			//$this->date = $data['date'] ?? '';
    	//$this->duration = (int)$data['duration'] ?? '';
    	//$this->topic = $data['topic'] ?? '';
    	//$this->comment = $data['comment'] ?? '';
  //} 
  
  
	private function check_errors() : void
   {
     if (!$this->data[':name'])
   	 {
   		  $this->data_errors[] = 'Название не заполнено';
   	  }
   		if (!$this->data[':place'])
   		{
   			$this->data_errors[] = 'Место не заполнено';
   		}
   		if (!$this->data[':date'])
   		{
   			$this->data_errors[] = 'Дата не заполнена';
   		}
   }

  public function validate() : bool
  {
     return !(count($this->data_errors)) ;
  }

  
  
  private function hasErrors() : void
   {
     echo '<ul style="color:red;">';
     foreach ($this->data_errors as $error) 
     {
     	echo '<li>' . $error . '</li>';
     }
     echo '</ul>';
   }

  public function getErrors() : string
  {
     $data = '<ul style="color:red;">';

     foreach ($this->data_errors as $error) 
     {
       $data .= '<li>' . $error . '</li>';
     }

     $data .= '</ul>';

     return $data;
  }

  public function save() : bool
  {
    $this->check_errors();

     if ($this->validate())
     {
       $sql = Database::exec('INSERT INTO user1 (`name`, `place`, `date`, `duration`, `topic`, `comment`, `if_done`, `created_at`) VALUES (:name, :place, :date, :duration, :topic, :comment, 1, NOW());', $this->data);
       return true;
     }
     else
     {
        
       return false;
     }
   
    //$pdo = new PDO("mysql:host=127.0.0.1;dbname=test_3", "root", "user2020");
    //$sql = Database::prepare('INSERT INTO user1 (`name`, `place`, `date`, `duration`, `topic`, `comment`, `if_done`, `created_at`) VALUES (:name, :place, :date, :duration, :topic, :comment, :if_done, NOW());');
    //$sql->execute([
    //  'name'=>$this->name, 
    //  'place'=>$this->place, 
    //  'date'=>$this->date, 
    //  'duration'=>(int)$this->duration, 
    //  'topic'=>$this->topic, 
    //  'comment'=>$this->comment,
    //  'if_done'=>(int)$this->if_done,
    //]);
    //$pdo->prepare('INSERT INTO apps (`ip`, `name`, `lastname`, `email`, `phone`, `topic`, `pay`, `confirm`, `created_at`) VALUES (\''.this->id.'\',\''.this->name.'\',\''.this->lastname.'\',\''.this->email.'\',\''.this->phone.'\',\''.this->topic_id.'\',\''.this->pay_id.'\',\''.this->confirm.'\',\''.this->created_at.'\')');
    //$this->ensureDataDir();
    //file_put_contents(static::$datafile, $this->toString(), FILE_APPEND);
  }

  public function update($task_id, $if_done) : bool
  {
    $this->check_errors();
    if ($this->validate())
    {
      $this->data[":if_done"] = $if_done;
      $this->data[":id"] = $task_id;

      Database::exec("UPDATE `user1` SET name = :name, place = :place, date = :date, duration = :duration, topic = :topic, comment = :comment, if_done = :if_done, updated_at = NOW() WHERE id = :id LIMIT 1;", $this->data);

      return true;
    }
    else
    {
       return false;
    }
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
  
  public static function deleteByIds(array $ids = [])
	{    
  if (!$ids)
  {
    return;
  }
  else
  {
    $ids_placeholder=trim(str_repeat('?,', count($ids)),',');
    $sql = Database::prep('
    UPDATE user1 SET deleted_at = NOW() WHERE id IN (' . $ids_placeholder . ') AND deleted_at IS NULL;');
    $sql->execute($ids);
  }
    //$items = static::loadAll();
    //foreach($ids as $id)
    //{
    //  foreach($items as $index => $item)
    //  {
    //    if($item->id === $id)
    //    {
    //      $item->status = 'deleted';
    //      $items[$index] = $item;
    //    }
    //  }
    //}
  //static::saveAll($items);
 }
  
  //public static function saveAll(array $items = [])
  //{
  //  $lines = [];
  //  foreach ($items as $item)
   // {
  //    $lines[] = $item->toString();
   // }
  //  file_put_contents(static::$datafile, implode('', $lines));
  //}
  
  public static function loadAll() : array
  {
    $data = Database::exec("SELECT * FROM `user1`");  
    return $data;
    //return Database::query('SELECT * FROM user1 WHERE deleted_at IS NULL;', static::class);
    //$sql = 'SELECT * FROM `:login` WHERE deleted_at IS NULL;';
    //$sql->execute([
    //  'login'=>$this->login,
    //]);
    //$items = [];
    //$contents = file_get_contents(static::$datafile);
    //$lines = explode("\n", trim($contents));
    //foreach($lines as $line)
    //{
    //  $cols = explode(static::SEPARATOR, trim($line));
    //  $item = new static; 
    //  $item->fill([ 
    //    'id' => $cols[0],
    //    'ip' => $cols[1],
    //    'date' => $cols[2],
    //    'name' => $cols[3],
    //    'lastname' => $cols[4],
    //    'email' => $cols[5],
    //    'phone' => $cols[6],
    //    'topic' => $cols[7],
    //    'pay' => $cols[8],
    //    'confirm' => $cols[9],
    //    'status' => $cols[10],
    //  ]);
    //$items[] = $item;
    //}
    //return $items;
  }
  //вв
  public static function loadAllDone() : array
  {
    
    return Database::query('SELECT * FROM user1 WHERE deleted_at IS NULL AND `if_done` = 1;', static::class); 
   
  }
  //вт
  public static function loadAllNotDone() : array
  {
    
    return Database::query('SELECT * FROM user1 WHERE deleted_at IS NULL AND `if_done` = 0 AND `date` > NOW();', static::class);
   
  }
  //вп
  public static function loadAllNotDoned() : array
  {
    
    return Database::query('SELECT * FROM user1 WHERE deleted_at IS NULL AND `if_done` = 0 AND `date` < NOW();', static::class);
   
  }
  //ст
  public static function loadAllTodayNotDone() : array
  {
    
    return Database::query('SELECT * FROM user1 WHERE deleted_at IS NULL AND `if_done` = 0 AND date(`date`) = date(NOW()) AND `date` > NOW();', static::class);
   
  }
  //сп
  public static function loadAllTodayNotDoned() : array
  {
    
    return Database::query('SELECT * FROM user1 WHERE deleted_at IS NULL AND `if_done` = 0 AND date(`date`) = date(NOW()) AND `date` > NOW();', static::class);
   
  }
  //св
  public static function loadAllTodayDone() : array
  {
    
    return Database::query('SELECT * FROM user1 WHERE deleted_at IS NULL AND `if_done` = 1 AND date(`date`) = date(NOW());', static::class);
   
  }
  //зт
  public static function loadAllTomorrowNotDone() : array
  {
    
    return Database::query('SELECT * FROM user1 WHERE deleted_at IS NULL AND `if_done` = 0 AND date(`date`) = date(NOW()+INTERVAL 1 DAY);', static::class);
   
  }
  //зв
  public static function loadAllTomorrowDone() : array
  {
    
    return Database::query('SELECT * FROM user1 WHERE deleted_at IS NULL AND `if_done` = 1 AND date(`date`) = date(NOW()+INTERVAL 1 DAY);', static::class);
   
  }
  //эт
  public static function loadAllWeekNotDone() : array
  {
    
    return Database::query('SELECT * FROM user1 WHERE deleted_at IS NULL AND `if_done` = 0 AND week(`date`) = week(NOW()) AND `date` > NOW();', static::class);
   
  }
  //эп
  public static function loadAllWeekNotDoned() : array
  {
    
    return Database::query('SELECT * FROM user1 WHERE deleted_at IS NULL AND `if_done` = 0 AND week(`date`) = week(NOW()) AND `date` > NOW();', static::class);
   
  }
  //эв
  public static function loadAllWeekDone() : array
  {
    
    return Database::query('SELECT * FROM user1 WHERE deleted_at IS NULL AND `if_done` = 1 AND week(`date`) = week(NOW());', static::class);
   
  }
  //ст
  public static function loadAllNextWeekNotDone() : array
  {
    
    return Database::query('SELECT * FROM user1 WHERE deleted_at IS NULL AND `if_done` = 0 AND week(`date`) = week(NOW()+INTERVAL 1 WEEK);', static::class);
   
  }
  //св
  public static function loadAllNextWeekDone() : array
  {
    
    return Database::query('SELECT * FROM user1 WHERE deleted_at IS NULL AND `if_done` = 1 AND week(`date`) = week(NOW()+INTERVAL 1 WEEK);', static::class);
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
            $check = $this->correctAdmin($this->login, $this->password);
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
  	public static function loadStatistic(): array
    {
        $statistic = [];
        $n_session = Database::query('SELECT COUNT(`session`) FROM statistic;')->fetch(PDO::FETCH_ASSOC);
        $statistic['session'] = $n_session['COUNT(`session`)'];
        $n_ip = Database::query('SELECT COUNT(DISTINCT `ip`) FROM statistic;')->fetch(PDO::FETCH_ASSOC);
        $statistic['ip'] = $n_ip['COUNT(DISTINCT `ip`)'];
        $n_hit = Database::query('SELECT SUM(`hit`) FROM statistic;')->fetch(PDO::FETCH_ASSOC);
        $statistic['hit'] = $n_hit['SUM(`hit`)'];
        return $statistic;
    }
  
    public static function insStatistic($session, $session_ip, $session_hit)
    {
        $sql = Database::prep('INSERT INTO statistic (ip, session, hit, created_at) VALUES (:ip, :session, :hit, NOW());');
        $sql->execute([
            'ip'=> $session_ip, 
            'session'=> $session, 
            'hit'=> $session_hit, 
        ]);
      
    }

    public static function updateHit($session)
    {
        $sql = Database::prep('UPDATE statistic SET updated_at = NOW(), hit = hit + 1 WHERE session = :session;');
        $sql->execute([
            'session'=> $session, 
        ]);
    }
  
  
}