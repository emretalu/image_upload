<?php
require_once "class/class.upload.php";

	$x = $_POST["x"];
	$y = $_POST["y"];
	$x2 = $_POST["x2"];
	$y2 = $_POST["y2"];
	$w = $_POST["w"];
	$h = $_POST["h"];
	$resimlink = $_POST["resimlink"];

	imageCrop($x,$y,$x2,$y2,$w,$h,$resimlink);
	
	function imageResize($link){
		$handle = new Upload($link);
		if($handle->image_src_x > 70 ) {
			$handle->image_resize = true;
			$handle->image_ratio_y = true;
			$handle->image_x = 70;
			
			$rand = uniqid(true);
            $handle->file_new_name_body = 'thumb_'.$rand;
			
			$handle->allowed = array('image/*');
						
			$handle->Process("upload/");
		}
	}

	function imageCrop($x,$y,$x2,$y2,$w,$h,$resimlink){
        if($w < 70 || $h < 70) {
            echo 0;
        }
        else{
            $handle = new Upload($resimlink);
            if ($handle ->uploaded) {
                $rand = uniqid(true);
                $handle->file_new_name_body = 'crop_'.$rand;
                                  
                $resimWidth = $handle->image_src_x - $x2;
                $resimHeight = $handle->image_src_y - $y2;
                
                $handle->image_crop = "{$y} {$resimWidth} {$resimHeight} {$x}";
                
                $handle->allowed = array('image/*');
                
                $handle->Process("upload/");

                if($handle->processed){
					imageResize('upload/crop_'.$rand.'.'.$handle->image_src_type);
					echo 1;
                }
                else{
                    echo 0;
                }
            }
            else{
                echo 0;
            }
        }
		exit;
	}

?>