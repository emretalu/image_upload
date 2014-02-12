<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
		<title>Upload Crop Image</title>
		<link rel="stylesheet" href="css/default.css" type="text/css" />
		<link rel="stylesheet" href="css/jquery.Jcrop.min.css" type="text/css" media="screen" />
		<script type="text/javascript" src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="js/jquery.Jcrop.min.js"></script>
		<script type="text/javascript">
			function runCrop(selector)
			{
				$(selector).Jcrop({
					onChange : showCoords,
					onSelect : showCoords,
					aspectRatio : 1
				});
				
				resimKirp();
			}

			function showCoords(c) {
				$('.popup #x').val(c.x);
				$('.popup #x2').val(c.x2);
				$('.popup #y').val(c.y);
				$('.popup #y2').val(c.y2);
				$('.popup #w').val(c.w);
				$('.popup #h').val(c.h);
				$('.popup #w2').text((c.w).toFixed(2));
		        $('.popup #h2').text((c.h).toFixed(2));
			}
		</script>
	</head>
	<body>
		<?php
            require_once "class/class.upload.php";
            
			if(@$_POST["submit"]) {
			    
				$resimler = array();
                
				foreach ($_FILES['resim'] as $k => $l) {
				  foreach ($l as $i => $v) {
					if (!array_key_exists($i, $resimler))
					  $resimler[$i] = array();
					$resimler[$i][$k] = $v;
				  }
				}

				$i = 1;				
				foreach ($resimler as $resim){
					$handle = new Upload($resim);
					if ($handle->uploaded) {
						$rand = uniqid(true);
						$handle->file_new_name_body = 'original_'.$rand;
						
						if($handle->image_src_x < 300 || $handle->image_src_y < 300){
							echo "Hata";
						}
						else{
							if($handle->image_src_y > $handle->image_src_x){
								if($handle->image_src_x > 300 ) {
									$handle->image_resize = true;
									$handle->image_ratio_y = true;
									$handle->image_x = 300;
								}
							}
							elseif($handle->image_src_x > $handle->image_src_y){
								if($handle->image_src_y > 300 ) {
									$handle->image_resize = true;
									$handle->image_ratio_x = true;
									$handle->image_y = 300;
								}
							}
							else{
								if($handle->image_src_x > 300 ) {
									$handle->image_resize = true;
									$handle->image_ratio_y = true;
									$handle->image_x = 300;
								}
							}
						
							$handle->allowed = array('image/*');
							
							$handle->Process("upload/");
							
							if ($handle->processed) { ?>
								<table width="160px" style="float:left">
									<tr>
										<td>
											<a href="upload/original_<?=$rand?>.<?=$handle->image_src_type?>"><img src="upload/original_<?=$rand?>.<?=$handle->image_src_type?>" alt="" width="150px"/></a>
										</td>
									</tr>
									<tr>
										<td align="center">
											<input type="button" value="Kırp" 
											onclick="popup($('#kirpForm<?=$i;?>'), <?=$handle->image_src_x; ?>, <?=$handle->image_src_y; ?>); $('.popup img').addClass('crop'); runCrop('.crop');"  />
										</td>
									</tr>	
								</table>
								<div id="kirpForm<?=$i;?>" style="display:none">
									<form method="post" id="kirpBeni">
										<img src="upload/original_<?=$rand?>.<?=$handle->image_src_type?>" id="crop"/>
										<input type="hidden" name="x" id="x" />
										<input type="hidden" name="y" id="y" />
										<input type="hidden" name="x2" id="x2" />
										<input type="hidden" name="y2" id="y2" />
										<input type="hidden" name="w" id="w" />
										<input type="hidden" name="h" id="h" />
										<input type="hidden" name="resimlink" value="upload/original_<?=$rand?>.<?=$handle->image_src_type?>" />
										<span id="w2">0</span> x <span id="h2">0</span> px 
										<input type="submit" value="Kaydet" name="resimcrop" />
										<span>Resminizi en az 70x70px boyutlarında seçmelisiniz.</span>
										<p class="message" style="display:none"></p>
									</form>
								</div>
							<?php } else {
								  echo $handle->error;
							}
						}
					}
					else {
						echo $handle->error;
					}
					$i++;
				}
			}

			if(!$_POST) { ?>
				<form method="post" action="" enctype="multipart/form-data">
					<input type="file" name="resim[]" multiple />
					<input type="submit" name="submit" value="Yükle" />
				</form>
			<?php } ?>
			
			<script type="text/javascript" src="js/pop-up.js"></script>
			<script type="text/javascript" src="js/crop.js"></script>
	</body>
</html>