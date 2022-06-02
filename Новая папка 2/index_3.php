<?php 
  include 'classes/fun.php';
  include 'classes/app.php';
include 'classes/aut.php';
  include_once 'classes/database.php';
    include 'classes/config.php';
  mb_internal_encoding('UTF-8');
	session_start();
	

    if (!empty($_SESSION['success']))
	{
		$success = true;
        $_SESSION['success'] = null;
	}

  

    if ($_POST)
    {
    
  	    $aut = new Aut($_POST);
       
        if ($aut->validateReg())
        {
            $aut->saveReg();
            $_SESSION['success'] = 1;
            //header('Location: index_2.php?success=1');
            header('Location: index_3.php');
            exit;
  	    }
    }			
    else
    {
        $aut = new Aut;
    }
	
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Admin</title>
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
	<?php if (!empty($success)): ?>
  		<p>Форма успешно отправлена!</p>
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
				<h1>Регистрация</h1>
				
				<form method="POST">
					
						<label>Логин</label>
						<input type="text" name ="login" value="<?=htmlspecialchars($aut->login)?>">
					
						<label>Пароль</label>
						<input type="password" name ="password" value="<?=htmlspecialchars($aut->password)?>">
					
						<button type="submit">Зарегестрироваться</button>
						
				</form>
	<?php endif?>
      <button onclick="window.location.href='https://danilenko.hostfl.ru/ip/index_1.php';">Войти в существующий аккаунт</button>
</body>
</html>