<?php
/**
 * author : L200130083@gmail.com
 * special thanks to stackoverflow.com
 */
defined("BASEPATH") OR die('dwaefwf');
function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
}
function dir_walker($dir) {
  
   $result = array();

   $cdir = scandir($dir);
   foreach ($cdir as $key => $value)
   {
      if (!in_array($value,array(".","..")))
      {
         if (is_dir($dir . DIRECTORY_SEPARATOR . $value))
         {
            $result[$value] = dir_walker($dir . DIRECTORY_SEPARATOR . $value);
         }
         else
         {
            $result[] = $value;
         }
      }
   }
  
   return $result;
} 
function create_dir($path)
{
	$real_chdir = getcwd();
	
	$ch = '';
	$dir_array = explode('/', $path);
	foreach ($dir_array as $dir)
	{
		if ($dir != '') $ch = $ch.$dir.'/';
		if (is_dir($dir))
		{
			chdir($dir);
		}
		else
		{
			if ($dir != '')
			{
				mkdir($dir,0775);
				chdir($dir);
				file_put_contents('index.php','Silent & Deadly',LOCK_EX);
			}
		}
	}
	chdir($real_chdir);
	return $ch;
	
}
function queryPagina($base_url,$total_row,$perpage)
{

	$CI =& get_instance();
	$config = array();
	$config['use_page_numbers']  = true;
        //$config['display_pages'] = FALSE;
	$config['page_query_string'] = TRUE;
	$config['query_string_segment'] = 'p';
        $config["base_url"] = $base_url;
        $config["total_rows"] = $total_row;
        $config["per_page"] = $perpage; 
        $config['first_url'] = $base_url.'?p=1';
        $config['uri_segment'] = 4;
        $config['num_links'] = 2;
        //link costumization
        //Number Style
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        //current Style
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '<span class="sr-only">(current)</span></a></li>';
        //Costumizing the Prev Link
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li>';
	$config['prev_tag_close'] = '</li>';
        //Costumizing the First Link
	$config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        //Customizing the Last Link
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        //Costumizing the Next Link
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
	$config['full_tag_open'] = '<nav><ul class="pagination">';
	$config['full_tag_close'] = '</ul></nav>';


    $CI->load->library('pagination');
	$CI->pagination->initialize($config);
        
        
	$pp = isset($_GET['p']) ? strip_chars($_GET['p']) : FALSE;
	if ($pp == FALSE) 
	{
		$pp = 0  ;
	}
	else
	{
		$pp = ($pp-1)*$config["per_page"];
	}
        
	$links = $CI->pagination->create_links();
	$data = array(
		'links' => $links,
		'row_start' => $pp,
		);
	return $data;

}
function recursiveRemoveDirectory($directory)
{
    foreach(glob("{$directory}/*") as $file)
    {
        if(is_dir($file)) { 
            recursiveRemoveDirectory($file);
        } else {
            @unlink($file);
        }
    }
    rmdir($directory);
}
function getDomain($url)
{
	$needles = array('https://www', 'https://', 'http://www', 'http://');
	$res = str_ireplace($needles,'', $url);
	$res = explode('/', $res);
	return $res[0];
}
function time_second($secs)
{
	$minutes = $secs/60;
	$hours = $minutes/60;
	$days = $hours/24;
	$weeks = $days/7;
	$months = $weeks/30;
	$years = $months/12;
	$data['seconds'] = $secs % 60;
	$data['minutes'] = $minutes % 60;
	$data['hours'] = $hours % 60;
	$data['days'] = $days % 24;
	return $data;
}
function unzipper($file, $extract_to)
{
	$zip = new ZipArchive;
	$res = $zip->open($file);
	if ($res === TRUE)
	{
		$zip->extractTo($extract_to);
		$zip->close();
		//extraction successfull
		return TRUE;
	}
	else
	{
		return FALSE;
	}
}
function image_src($path)
{
	$rv = explode('./', $path);
	if ( ! file_exists($path)) return base_url().'assets/no_image_thumb.gif';
	return base_url() . $rv[1];
}
function space2underscore($str)
{
	return str_ireplace(' ', '_', $str);
}
function strip_chars($string)
{

	return preg_replace("/[^-a-zA-Z_() ^0-9]/", "", $string);

}
function valid_foldername($str)
{
	if (preg_match("/[^-a-zA-Z_() ^0-9]/",$str))
	{
   		return TRUE;
	} 
	else
	{
		return FALSE;
	}
}
function my_encode($str)
{
	return str_ireplace(' ', '-', str_ireplace('-','+', $str));
	//return urlencode($str);
}
function my_decode($str)
{
	return str_ireplace('+','-',str_ireplace('-', ' ', $str));
	//return urldecode($str);
}
function get_day_name($timestamp, $time_included = false) {
    
    $day_name = '';
    $day_format = 'd/m/Y';
    $time = strtotime($timestamp);
    $date = date($day_format, $time);
    
    if($date == date($day_format)) {
      $day_name = 'Today';
    } elseif($date == date($day_format, time() - (24 * 60 * 60))) {
      $day_name = 'Yesterday';
    } 
    elseif($date == date($day_format, time() - (2*(24 * 60 * 60)))) {
      $day_name = '2 days ago';//day before yesterday
    }
    elseif($date == date($day_format, time() - (3*(24 * 60 * 60)))) {
      $day_name = '3 days ago';//day before yesterday
    }
    elseif($date == date($day_format, time() - (4*(24 * 60 * 60)))) {
      $day_name = '4 days ago';//day before yesterday
    }
    elseif($date == date($day_format, time() - (5*(24 * 60 * 60)))) {
      $day_name = '5 days ago';//day before yesterday
    }
    elseif($date == date($day_format, time() - (6*(24 * 60 * 60)))) {
      $day_name = '6 days ago';//day before yesterday
    }
    elseif($date == date($day_format, time() - (7*(24 * 60 * 60)))) {
      $day_name = '1 week ago';//day before yesterday
    }

    if($time_included)
        return ($day_name=='') ? $date : $day_name . ' at '.date('H:i', $time);
    else
        return ($day_name=='') ? $date : $day_name;
}
function sidebar()
{
	$widget =& get_instance();
	$widget->load->model('site_model');
	$get = $widget->site_model->read('widgets', array('status' => 1), '*', array('order', 'ASC'));
	$rv = $widget->load->view('admin/widgets/show1', array('wgs' => $get), TRUE);
	return $rv;
}

function menu($loc = 'top')
{
	$menus =& get_instance();
	$menus->load->database();
	if (! in_array($loc, array('top', 'bottom')))
	{
		$loc = 'top';
	}
	$get = "SELECT menus.* FROM menus, menu_location WHERE menus.id = menu_location.menus_id AND menu_location.location = '{$loc}' ORDER BY `the_order` ASC";
	$rv = $menus->load->view($loc == 'top' ? 'top_menus' : 'bottom_menus', array('menus' => $menus->db->query($get)), TRUE);
	return $rv;
}
function cache_cleaner()
{
	$ci =& get_instance();
	$ci->load->helper('directory');
	$caches = directory_map(APPPATH.'cache');
	foreach($caches as $k => $v)
	{
		if (strpos($v, '.') === FALSE)
		{
			unlink(APPPATH.'cache/'.$v);
		}
	}
}
function flash_msg(){
	
}


	
