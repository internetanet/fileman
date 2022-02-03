<?php
header('Content-Type: text/html; charset = utf-8');
error_reporting(E_ALL);
mb_internal_encoding("UTF-8");

define('ROOT_DIR', dirname(__FILE__));

function msg($message, $param = 2) {//функция вывода сообщения
	if ($param == 1) {
		$show = '<div class="message-green"><span class="message-text">'.$message.'</span></div>';
	}
	elseif ($param == 0) {
		$show = '<div class="message-red"><span class="message-text">'.$message.'</span></div>';
	}
	else{
		$show = '<div class="message-blue"><span class="message-text">'.$message.'</span></div>';
	}
	echo $show;
}


function file_type($file_name) {//функция определения расширения файла
    $a = mb_substr(mb_strrchr($file_name, '.'), 1);
    return $a;
}

if (isset($_POST['submit']) && !empty($_FILES['new_file']['tmp_name'])) {
	if (empty($_POST['file_name'])) {
		$message = 'Пожалуйста, выберите нужный формат имени файла';//выводимое сообщение
		msg($message);//функция вывода сообщения
	}
	else{
		if ($_POST['file_name'] == 'randname') {
			if ($_POST['num'] < 1) {
				$message = 'Выберите длину имени файла';//выводимое сообщение
				msg($message);
				exit();
			}
			else{
				$new_name = substr(bin2hex(random_bytes($_POST['num'])), 0, $_POST['num']);//случайный набор букв-цифр выбранной длины
			}
		}
		elseif ($_POST['file_name'] == 'date-time') {
			$new_name = date('d-m-y_h-i');
		}
		elseif ($_POST['file_name'] == 'date') {
			$new_name = date('d-m-y');
		}
		else {
		//
		}

		$file_type = '.'.file_type($_FILES['new_file']['name']);//расширение файла
		
		$destination = ROOT_DIR.'/'.$new_name.$file_type;//директория, куда нужно поместить файл+приписали расширение
		
		if (is_uploaded_file($_FILES['new_file']['tmp_name'])) {
			move_uploaded_file($_FILES['new_file']['tmp_name'], $destination);//перемещаем файл в выбранную директорию из его временного хранилища

			$message = 'Файл успешно загружен. Имя файла: '.$new_name.$file_type.'. <a href="'.$new_name.$file_type.'" download="'.$new_name.$file_type.'" title="нажмите, чтобы скачать">скачать</a>.';//выводимое сообщение
			msg($message,1);//
		}
		else{
			$message = 'Ошибка во время загрузки файла';//выводимое сообщение
			msg($message,0);
		}

	}
}
elseif (isset($_POST['submit']) && empty($_FILES['new_file']['tmp_name'])) {
	$message = 'Не выбран файл для загрузки';//выводимое сообщение
	msg($message,0);//
}
else{
	///
}



?>

<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Загрузка файлов из формы</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<div class="wrap">
		
		<form method="POST" action="/" enctype="multipart/form-data">
			<h3>Загрузить файл</h3>
			<input type="hidden" name="MAX_FILE_SIZE" value="30000000" /><!-- ограничение в ~ 30 мегабайт -->
			<input type="file" name="new_file" accept=".*"><br>
				
			<h3>Имя файла:</h3>
			<label><input type="radio" name="file_name" value="date">текущая дата в формате ДД-ММ-ГГ</label><br>
			<label><input type="radio" name="file_name" value="date-time">текущая дата в формате ДД-ММ-ГГ_ЧЧ-ММ</label><br>
			<label><input type="radio" name="file_name" value="randname">случайная строка длиной <input type="number" name="num" size="1" value="5" class="input-number"> символов</label><br><br>
			<input type="submit" name="submit" value="Сохранить">
		</form>
	</div>
	
</body>
</html>