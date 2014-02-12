function resimKirp(){
	$(".popup form").submit(function(e) {
		$.ajax({
			type: "POST",
			url : "functions.php",						
			data : $('.popup form').serialize(),
			success: function(data)
			{
				if(data == 0){
					$('.popup form .message').addClass('warning').show();
					$('.popup form .message').text('70x70 boyutundan küçük kırpamazsınız.');
				}
				else if(data == 1){
					$('.popup form .message').addClass('success').show();
					$('.popup form .message').text('Resim kırpma işlemi başarılı.');
				}
				else{
					$('.popup form .message').addClass('warning').show();
					$('.popup form .message').text('Hata oluştu. Tekrar deneyiniz.');
				}
			}
		});
	  e.preventDefault();
	});
}