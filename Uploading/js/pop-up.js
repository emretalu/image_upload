var Source = "", Width = 0, Height = 0, gallery = false, pictureNumber;

function popup(source, width, height){
	if(source.jquery){
		openPopup(source.html(), width, height)
		return 0
	}
	if(source.indexOf("php") !== -1 && source.indexOf("<") !=  0 || source.indexOf("html") !== -1 && source.indexOf("<") !=  0)
	{
		$.get(source, function(data) {
			openPopup(data, width, height)
		});
	}
	else
	{
		openPopup(source, width, height)
	}
}

function openPopup(data, width, height){
	$('#popup').remove();
	$('#overlay').remove();
	
	var overlay = document.createElement("div");
	overlay.className = "overlay";
	overlay.id = "overlay";
	
	var panel = document.createElement("div");
	panel.className = "popup";
	panel.id = "popup";
	
	var content = document.createElement("div");
	content.className = "content";
	var scroller = document.createElement("div");
	scroller.className = "scroller";
	
	content.innerHTML = data;
	
	if(height && height != "auto")
	{
		panel.style.height = height + "px";
		panel.style.marginTop = (-height/2) - 20 + "px";
	}
	if(width && width != "auto")
	{
		panel.style.width = width + "px";
		panel.style.marginLeft = (-width/2) + "px";
	}
	
	var closebtn = document.createElement("div");
	closebtn.className = "popup-close";
	
	scroller.appendChild(content);
	panel.appendChild(scroller);
	panel.appendChild(closebtn);
	document.body.appendChild(panel);
	document.body.appendChild(overlay);

	$('#overlay, .popup-close').click(function(){
		$('#overlay').remove();
		$('#popup').remove();
	})
	
	var popup = $('#popup');
	var content = $('#popup .content');
	var scroller = $('#popup .scroller');
	var scrol;
	
	if(height == 'auto' || !height)
	{
		if(popup.height() > 500){
			popup.height(500);
		}
		popup.css('margin-top',-1*(popup.outerHeight(false))/2)
	}
	
	if(content.height() > popup.height())
	{
		scroller.height(popup.height());
		scroller.addClass('scroll')
		scrol = true	
	}
	
	if(width == 'auto' || !width)
	{
		if (popup.width() < 650)
		{
			popup.css('width', popup.width())
			popup.css('margin-left', -1*popup.outerWidth(false)/2)
		}
		else
		{
			popup.css('width', 650)
			popup.css('margin-left', -335)
		}
	}
	if(scrol){content.width(scroller.width() - 20)}
	
	$('#popup img').load(function(){
		resizePopup()
	})
	
	if(gallery){
		getButtons()
		if(pictureNumber + 1 == $('.image-gallery').length){$('#btnR').remove()}
		if(pictureNumber == 0){$('#btnL').remove()}
	}
	
	jQuery.globalEval($('#popup .scroller').find('script').text())
}

function resizePopup(){
	var popup = $('#popup');
	var content = $('#popup > .scroller > .content');
	var scroller = $('#popup .scroller');
	var scrol;
	
	Height = content.outerHeight(false);	
	
	if(Height > $(window).height()*0.85){

		Height = $(window).height()*0.85
		popup.height(Height)
		//scroller.height(Height)
	}
	else{popup.height(Height); /*scroller.height(Height)*/}
	
	if(content.height() > scroller.height())
	{
		scroller.height(popup.height());
		scroller.addClass('scroll')
		scrol = true	
	}

	if (popup.width() <= 900)
	{
		popup.css('width', popup.width())
		popup.css('margin-left', -1*popup.outerWidth(false)/2)
	}
	else
	{
		popup.css('width', 900)
		popup.css('margin-left', -450)
	}
	
	if(scrol){content.width(scroller.width() - 20)}
	
	popup.css('marginTop', (-Height/2) - 20 + "px")
	
}

$(document).ready(function(){
	var a = $('.image-gallery')
	a.click(function(e){
		pictureNumber =  a.index(e.target)
		gallery = true
	})	
})

function getButtons(){
	var btnR = document.createElement("div");
	btnR.className = "gallery-btn";
	btnR.id = "btnR";
	
	var btnL = document.createElement("div");
	btnL.className = "gallery-btn";
	btnL.id = "btnL";
	
	$('#popup').append(btnR);
	$('#popup').append(btnL);
	
	$('#btnR').click(function(){
		$('.image-gallery').eq(pictureNumber + 1).click()
	})
	
	$('#btnL').click(function(){
		$('.image-gallery').eq(pictureNumber - 1).click()
	})
	
	gallery = false
}

$(document).keydown(function(e){
    if (e.keyCode == 37) { 
       $('#btnL').click()
       return false;
    }
	if (e.keyCode == 39) { 
       $('#btnR').click()
       return false;
    }
});