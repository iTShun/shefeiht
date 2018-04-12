<?php


function _Hint_($desc)
{
	echo "<script language='JavaScript'>alert('" . $desc . "');</script>";
}

function _Back_Up_()
{
	echo "<script>window.location.href='" . $_SERVER["HTTP_REFERER"] . "';</script>";
}

function _Goto_($url)
{
	echo "<script>window.location.href='" . $url . "';</script>";
}

function _Reload_()
{
	echo "<script>window.location.reload();</script>";
}

function _IsSet_($arr, $key)
{
	if ($arr && is_array($arr) && isset($arr[$key]))
	{
		return $arr[$key];
	}
	return null;
}

function _deep_in_array_($value, $array, $index = 0) 
{   
    foreach($array as $item) {  
        if(!is_array($item)) {   
            if ($item == $value) {  
                return array($index, $item);  
            } else {  
                continue;   
            }  
        }   
            
        if(in_array($value, $item)) {  
            return array($index, $item);     
        } else if(_deep_in_array_($value, $item, $index)) {  
            return array($index, $item);      
        }  
        $index++;
    }   
    return null;   
}

function _array_search_re_($needle, $haystack, $a=0, $nodes_temp=array())
{ 
	//根据键值返回键名
	global $nodes_found;
	$a++;
	foreach ($haystack as $key1=>$value1) { 
	    $nodes_temp[$a] = $key1; 
	    if (is_array($value1)){    
	      array_search_re($needle, $value1, $a, $nodes_temp); 
	    } 
	    else if ($value1 === $needle){ 
	      $nodes_found[] = $nodes_temp; 
	    } 
	} 
	return $nodes_found; 
}

function _array_search_key_($needle, $haystack)
{ 
	//根据键名返回键值
	global $nodes_found; 
	foreach ($haystack as $key1=>$value1) { 
		if ($key1=== $needle){ 
			$nodes_found[] = $value1; 
		} 
		if (is_array($value1)){    
		    array_search_key($needle, $value1); 
		} 
	} 
	return $nodes_found; 
} 

?>