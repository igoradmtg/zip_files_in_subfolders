<?php
function add_log($str) {
  GLOBAL $fname_log;
  if (!isset($fname_log)) return false;
  if (empty($fname_log)) return false;
  $fh=fopen($fname_log,'ab');
  if ($fh!=false) {
    fwrite($fh,date("Y-m-d H:i:s").' '.$str." <br>\r\n");
    fclose($fh);
  }
    
}
// Все HTML переносы преобразовать в текстовые переносы
function br2rn($str)
{
  $str = preg_replace("/(\r\n|\n|\r)/", "", $str);
  $str = preg_replace("/(?ims)<br[^>]*>/U", "\r\n", $str);
  $str = preg_replace("/(?ims)<p[^>]*>/U", "\r\n\r\n", $str);
  $str = preg_replace("/(?ims)<\/p[^>]*>/U", '', $str);
  $str = preg_replace("/(?ims)\r\n\s/U", "\r\n", $str);
  $str = preg_replace("/(?ims)\r\n\s/U", "\r\n", $str);
  $str = preg_replace("/(?ims)\r\n\s/U", "\r\n", $str);
  return $str;
}
// Очистка от тега &nbsp;
function strip_nbsp($str)
{
  return str_replace('&nbsp;',' ',$str);
}
// Добавление нулей в начале
function add_zero($num,$kolvo)
{
  $num=abs($num);
  $len=strlen($num);
  if ($len<$kolvo) return trim(str_repeat('0',$kolvo-$len).$num);
  else return trim($num);
}
// Возвращает полное имя файла по каталогу
function get_fname($id,$dirname,$ext='.dat',$is_create_dir=true)
{
  $num_str=add_zero($id,10);
  $dir1=substr($num_str,0,2);
  $dir2=substr($num_str,2,2);
  $dir3=substr($num_str,4,2);
  $dir4=substr($num_str,6,2);
  $fname=$num_str;
  if ($is_create_dir)
    {
      if (is_dir($dirname.'/'.$dir1)==false)
      {
        if (mkdir($dirname.'/'.$dir1)==false) {echo "Error make dir ".$dirname.'/'.$dir1."<br>\r\n";return false;}
      }
      if (is_dir($dirname.'/'.$dir1.'/'.$dir2)==false)
      {
        if (mkdir($dirname.'/'.$dir1.'/'.$dir2)==false) {echo "Error make dir ".$dirname.'/'.$dir1.'/'.$dir2."<br>\r\n";return false;}
      }
      if (is_dir($dirname.'/'.$dir1.'/'.$dir2.'/'.$dir3)==false)
      {
        if (mkdir($dirname.'/'.$dir1.'/'.$dir2.'/'.$dir3)==false) {echo "Error make dir ".$dirname.'/'.$dir1.'/'.$dir2.'/'.$dir3."<br>\r\n";return false;}
      }
      if (is_dir($dirname.'/'.$dir1.'/'.$dir2.'/'.$dir3.'/'.$dir4)==false)
      {
        if (mkdir($dirname.'/'.$dir1.'/'.$dir2.'/'.$dir3.'/'.$dir4)==false) {echo "Error make dir ".$dirname.'/'.$dir1.'/'.$dir2.'/'.$dir3.'/'.$dir4."<br>\r\n";return false;}
      }
    }
  return $dirname.'/'.$dir1.'/'.$dir2.'/'.$dir3.'/'.$dir4.'/'.$fname.$ext;
}
// Функция получения логина или пароля 
// $is_login - возвращает только символы латиницы в нижнем регистре
function get_pass($num_simbol,$is_login=false)
{
  $str='abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ1234567890';$str_len=strlen($str);
  $r='';
  if ($is_login)
  {
    for($a=1;$a<=$num_simbol;$a++) $r.=$str[mt_rand(0,24)];
  }
  else
  {
    for($a=1;$a<=$num_simbol;$a++) $r.=$str[mt_rand(0,$str_len-1)];
  }
  return $r;
}

// Рекурсивное чтение каталога в массив
// $is_key - добавлять вложенные массивы TRUE, иначе в одномерные массив
function dir_to_array($dir,$is_add_dir=false,$is_key=true) 
{
  $r = array();
  $cdir = scandir($dir);
  foreach ($cdir as $key => $value)
  {
    //echo "$value <br>\r\n";
    if (!in_array($value,array('.','..')))
    {
       if (is_dir($dir . '/' . $value))
       {
          if ($is_key)
          {
            if ($is_add_dir) $r[$dir . '/' .$value] =  dir_to_array($dir . '/' . $value,$is_add_dir,$is_key);
            else $r[$value] = dir_to_array($dir . '/' . $value,$is_add_dir,$is_key);
          }
          else
          {
            $tmp_arr=dir_to_array($dir . '/' . $value,$is_add_dir,$is_key);
            foreach($tmp_arr as $tmp_fname)
            {
              $r[] = $tmp_fname;
            }
          }
       }
       else
       {
          if ($is_add_dir) $r[] = $dir . '/' . $value;
          else $r[] = $value;
       }
    }
  }
  return $r;
} 
// Читаем каталог в массив только один уровень
// $is_add_dir - добавлять в название полный путь к файлу или каталогу
// $is_file - добавлять только файлы, иначе добавлять только каталоги
function dir_to_array_nr($dir,$is_add_dir=false,$is_file=true)
{
  $r=array();
  if (!is_dir($dir)) return false;
  $cdir = scandir($dir);
  foreach ($cdir as $key => $value)
  {
    if (!in_array($value,array('.','..')))
    {
      if ($is_file)
      {
        if (is_file($dir . '/' . $value))
        {
          if ($is_add_dir) $r[] = $dir . '/' . $value;
          else $r[] = $value;
        }
      }
      else
      {
        if (is_dir($dir . '/' . $value))
        {
          if ($is_add_dir) $r[] = $dir . '/' . $value;
          else $r[] = $value;
        }
      }
    }
  }
  return $r;
}

// Создать каталог если он не существует
// Если каталог есть или был успешно создан возвращает TRUE иначе FALSE
function make_dir_if_not_exists($dirname)
{
  if (is_dir($dirname)) return true;
  else
  {
    if (mkdir($dirname)==false) return false;
    else return true;
  }
}

function delete_all_files_in_dir($dirname)
{
  $ar_file=dir_to_array_nr($dirname);
  if ($ar_file!=false)
  {
    foreach($ar_file as $fname) 
      unlink($dirname.'/'.$fname);
  }
}
// Удалить пустые каталоги в данном каталоге
function delete_empty_dir($dirname)
{
  if (is_dir($dirname)==false) return false;
  $ar_dir=dir_to_array_nr($dirname,true,false);
  if ($ar_dir==false) return true;
  foreach ($ar_dir as $dirname2) {
    echo "$dirname2 <br>\r\n";
    $ar_file=dir_to_array_nr($dirname2,true,true);
    if ($ar_file==false) {
      if (rmdir($dirname2)) {echo "Delete $dirname2 <br>\r\n";}
      else {echo "Error delete $dirname2 <br>\r\n";}
    }
  }
}
function translit($s) 
{
  $s = strval($s); // преобразуем в строковое значение
  $s = strip_tags($s); // убираем HTML-теги
  $s = str_replace(array("\n", "\r", "\t"), " ", $s); // убираем перевод каретки
  $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
  $s = trim($s); // убираем пробелы в начале и конце строки
  $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
  $s = strtr($s, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
  $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); // очищаем строку от недопустимых символов
  $s = str_replace(" ", "-", $s); // заменяем пробелы знаком минус
  return $s; // возвращаем результат
}
function format_size_units($bytes)
{
  if ($bytes >= 1073741824) {
      $bytes = number_format($bytes / 1073741824, 2) . ' GB';
  } elseif ($bytes >= 1048576) {
      $bytes = number_format($bytes / 1048576, 2) . ' MB';
  } elseif ($bytes >= 1024) {
      $bytes = number_format($bytes / 1024, 2) . ' KB';
  } elseif ($bytes > 1) {
      $bytes = $bytes . ' bytes';
  } elseif ($bytes == 1) {
      $bytes = $bytes . ' byte';
  } else {
      $bytes = '0 bytes';
  }
  return $bytes;
}
//***********************************************
// Обрезаем строку и добавляем в конец точки
function cut_str($str,$max_size=200,$encode='UTF-8',$str_dots=' ...') 
{
  $len_str_dots=mb_strlen($str_dots,$encode); // Длина строки с точками
  $pos=strrpos($str,' '); // Возвращает позицию последнего вхождения подстроки в строке
  $str_new = mb_strcut($str,0,$max_size-$len_str_dots,$encode).$str_dots;
  return $str_new;
}

/*
$dir="z:/02/";
$ar=dir_to_array_nr($dir,false,false);
foreach($ar as $d)
{
  echo "$dir $d <br>\r\n";
  if (@copy('img1.jpg',$dir.$d.'/0001.jpg')) echo "Copy <br>\r\n"; else echo "Error <br>\r\n";
}
*/
?>