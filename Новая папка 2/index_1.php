<?php 
  include 'classes/fun.php';
  include 'classes/app.php';
include 'classes/aut.php';
  include_once 'classes/database.php';
	include 'classes/config.php';
	include_once 'datas.php';
	
  mb_internal_encoding('UTF-8');
	session_start();

	
	if (!empty($_SESSION['is_admin']))
  {
    $is_admin = true;
    if (!empty($_SESSION['success']))
		{
			$success = true;
    	$_SESSION['success'] = null;
		}

  

		if ($_POST)
  	{
    	
      	if (!isset($_POST["task_id"]))
  			{
    			$app = new App($topics, $durations, $_POST);

    			if ($app->save())
    			{
      			$message = "<div style='color:green;'>Задача добавлена!</div>";
    			}
    			else
    			{
      			$message = $app->getErrors();
    			}
  			}
  			else 
      	{
    			$app = new App($topics, $durations, $_POST);

    			if ($app->update($_POST["task_id"], (isset($_POST["if_done"]))))
    			{
      			$message = "<div style='color:green;'>Данные задачи обновлены!</div>";
    			}
    			else
    			{
      			$message = $app->getErrors();
    			}
  			}

  			$_POST = null;
  			$data_rel = false;
     	  
  				
		
  			$is_admin = true;
    
    
    	//if(isset($_POST['selected']))
      //{
      //	App::deleteByIds($_POST['selected']);
      //  $_SESSION['last_time'] = time();
      //}
      if (isset($_POST['logout']))
			{
				session_destroy();
				header('Location: index_1.php');
				exit;
			}
    }
    
    if ($_GET)
		{
  		$query = "SELECT * FROM `user1`";

  		if (isset($_GET["day"]))
  		{
    		$query =  Database::add_sql($query, "date = '" . $_GET["day"] . "'");
  		}
  		else if (isset($_GET["date"]))
  		{
    		if ($_GET["date"] == "all") {}
    		else if ($_GET["date"] == "today") 
        {
          $query =  Database::add_sql($query, "date_format(date, '%y-%m-%d') between date_format(now(), '%y-%m-%d') and date_format(now() + interval 1 day, '%y-%m-%d')"); 
        }
    		else if ($_GET["date"] == "tomorrow") 
        { 
          $query =  Database::add_sql($query, "date_format(date, '%y-%m-%d') between date_format(now() + interval 1 day, '%y-%m-%d') and date_format(now() + interval 2 day, '%y-%m-%d')"); 
        }
        
    		else if ($_GET["date"] == "this_week") 
        { 
          $query =  Database::add_sql($query, "date_format(date, '%y-%m-%d') between date_format(now() - interval (DAYOFWEEK(now()) -2) day, '%y-%m-%d') and date_format(now() + interval (7 - (DAYOFWEEK(now()) -1)) day, '%y-%m-%d')");
        }
    		else if ($_GET["date"] == "next_week") 
        {
          $query =  Database::add_sql($query, "date_format(date, '%y-%m-%d') between date_format(now() - interval (DAYOFWEEK(now()) -2) day + interval 7 day, '%y-%m-%d') and date_format(now() + interval (7 - (DAYOFWEEK(now()) -1)) day + interval 7 day, '%y-%m-%d')");
  			}

  		if (isset($_GET["status"]))
  		{
    		if ($_GET["status"] == "now") { $query = Database::add_sql($query, "if_done = '1'"); }
    		else if ($_GET["status"] == "completed") { $query = Database::add_sql($query, "if_done = '2'"); }
    		else if ($_GET["status"] == "over") {
      		$query = Database::add_sql($query, "if_done = '1' AND (DATEDIFF(date, date_format(now(), '%y-%m-%d')) < 0 OR " .
        	"(DATEDIFF(date, date_format(now(), '%y-%m-%d')) = 0 AND TIMEDIFF(date, date_format(now(), '%H:%i')) < 0));");
    		}
  		}
  
  		$data = Database::exec($query);
  		$data_rel = true;
		}
  	
		if (($_SESSION['last_time'] + 600) < time())
		{
			session_destroy();
			header('Location: index_1.php');
			exit;
		}
   } 
  }
   else
   {
    	if($_POST)
      {
      	$aut = new Aut($_POST);
        if ($aut->validateAdmin())
        {
          $_SESSION['is_admin'] = 1;
          $_SESSION['last_time'] = time();
        	header('Location: index_1.php');
					exit;
        }
      }
      else
      {
      	$aut = new Aut;
      }
   }

if (!$data_rel)  
{
  $data = App::loadAll();
  $data_rel = true;
}
	
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Задачи</title>
        <style type="text/css">
          body{
            font-family: Arial;
          }
			    .header{
            font-size: 20px;
          }
          .check{
            font-size: 10px;
          }
          button{
            font-size: 15px;
          }
        </style>
    </head>  
    <body>
      
	<?php if (!empty($is_admin)): ?>
      <h2 class="task_cont_header">Новая задача</h2>
    <div class="messages">
      <?php if ($message) { echo $message; }?>
    </div>
      
        <form method="POST" action="" class="form_cont_form">
            <label>Название</label>
            <input type="text" name="name" value="<?= e($_POST['name'] ?? '') ?>">
            <br>
		    		<label>Тип:</label>
            
                <select class="form_cont_input_select" name="topic">
                <?php
                  foreach ($topics as $topic) {
                    echo '<option' . (strcmp($topic["name"], ($_POST['topic'] ?? '')) ? '' : ' selected') . '>' . $topic["name"] . '	</option>';
                  }
                ?>
              </select>
            
            <br>
         	 <label>Место</label>
            <input type="text" name="place" value="<?= e($_POST['place'] ?? '') ?>">
            <br>
            <label>Дата и время</label>
            <input type="datetime-local" name="date" value="<?= e($_POST['date'] ?? '') ?>">
            <br>
            <label>Длительность</label>
            <select class="form_cont_input_select" name="duration">
                <?php
                  foreach ($durations as $duration) {
                    echo '<option' . (strcmp($duration["name"], ($_POST['duration'] ?? '')) ? '' : ' selected') . '>' . $duration["name"] . '	</option>';
                  }
                ?>
            </select>
            <br>
          	<label>Комментарий</label>
            <input type="text" name="comment" value="<?= e($_POST['comment'] ?? '') ?>">
            <br>
          
            <br>
          	<hr>
          
            <button type="submit">
            Добавить 
            </button>
        </form>
    
		
				<h1>Календарь</h1>
      
        <select class="element_for_filter" name="sort_by_status">
            <?php foreach ($status_filter as $key => $value) { ?>
            <?php echo "<option value='" . $key . "'" . (strcmp(($_GET['status'] ?? ''), $key) ? '' : ' selected') . ">" . $value . "</option>"; ?>
            <?php } ?>
          </select>
          <input class="element_for_filter sort_by_date" type="date" value="<?php echo $_GET['day'] ?? '' ?>">
          <?php foreach ($date_filter as $key => $value) { ?>
          <?php echo "<span class='element_for_filter' value='" . $key . "'" . (strcmp(($_GET['date'] ?? ''), $key) ? '' : ' style="color: #153e9b; text-decoration: none;"') . ">" . $value . "</span>"; ?>
          <?php } ?>
        
			
			<form method="POST">
				<table border="1">
					<thead>
						<tr>
							
							
							<th>Название</th>
							<th>Тип</th>
							<th>Место</th>
							<th>Дата и время</th>
							<th>Длительность</th>
							<th>Комментарий</th>
							<th>Выполнено?</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($data as $key => $value) { ?>
						<tr>
              
              <td class="list_cont_tasks_td_taskName"><?= e($value["name"]); ?></td>
              <td <?= e("data__id=" . $value["id"]); ?>><?= e($topics[$value["topic"] - 1]["name"]); ?></td>
              <td><?= e($value["place"]); ?></td>
              <td><?= e($value["date"]); ?></td>
              <td><?= e($durations[$value["duration"] - 1]["name"]); ?></td>
              <td><?= e($value["comment"]); ?></td>
              <td><?= e($if_doned[$value["if_done"] - 1]["name"]); ?></td>
						</tr>
						<?php } ?>
					</tbody>				
				</table>
				
            
						<button type="submit" name="logout">Выйти</button>
						
			</form>
	
	<?php else: ?>			
		<?php if ($aut->hasErrorsAdmin()): ?>
        <p>
        Вы допустили ошибки:
        </p>
        <ul>
            <?php foreach ($aut->getErrorsAdmin() as $error): ?>
    	        <li><?= e($error) ?></li>
		    <?php endforeach ?>
        </ul>
    <?php endif ?>
				<h1>Вход</h1>
				
				<form method="POST">
					
						<label>Логин</label>
						<input type="text" name ="login" value="<?=e($aut->login)?>">
					
						<label>Пароль</label>
						<input type="password" name ="password" value="<?=e($aut->password)?>">
					
						<button type="submit" name="log">Войти</button>
						
				</form>
	<?php endif?>
      <button onclick="window.location.href='https://danilenko.hostfl.ru/ip/index_3.php';">Зарегистрировать новый аккаунт</button>
<script src="update_and_filter"></script>
  </body>
</html>