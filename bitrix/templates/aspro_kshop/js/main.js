// ASPRO.KSHOP JavaScript API v.1.0.0

var basketTimeoutSlide;
var resizeEventTimer;

var funcDefined = function(func){ 
	try {
		if (typeof func == 'function') {
			return true;
		} else {
			return typeof window[func] === "function";	
		}
	} catch (e) {
		return false;
	} 
}

if(!funcDefined('trimPrice')){ 
	var trimPrice = function trimPrice(s){
		s=s.split(" ").join("");
		s=s.split("&nbsp;").join("");
		return s;
	}
}

if(!funcDefined('markProductRemoveBasket')){ 
	var markProductRemoveBasket = function markProductRemoveBasket(id){
		$('.in-cart[data-item='+id+']').hide();
		$('.to-cart[data-item='+id+']').show();
		$('.to-cart[data-item='+id+']').closest('.button_block').removeClass('wide');
		$('.to-cart[data-item='+id+']').closest('.counter_wrapp').find('.counter_block').show();
		$('.counter_block[data-item='+id+']').show();
		$('.in-subscribe[data-item='+id+']').hide();	
		$('.to-subscribe[data-item='+id+']').show();
		$('.wish_item[data-item='+id+']').removeClass("added");
		$('.wish_item[data-item='+id+'] .value:not(.added)').show();
		$('.wish_item[data-item='+id+'] .value.added').hide();
	}
}

if(!funcDefined('markProductAddBasket')){ 
	var markProductAddBasket = function markProductAddBasket(id){		
		$('.to-cart[data-item='+id+']').hide();
		$('.to-cart[data-item='+id+']').closest('.counter_wrapp').find('.counter_block').hide();
		$('.to-cart[data-item='+id+']').closest('.button_block').addClass('wide');
		$('.in-cart[data-item='+id+']').show();
		$('.wish_item[data-item='+id+']').removeClass("added");
		$('.wish_item[data-item='+id+'] .value:not(.added)').show();
		$('.wish_item[data-item='+id+'] .value.added').hide();
	}
}

if(!funcDefined('markProductDelay')){ 
	var markProductDelay = function markProductDelay(id){		
		$('.in-cart[data-item='+id+']').hide();	
		$('.to-cart[data-item='+id+']').show();
		$('.to-cart[data-item='+id+']').closest('.counter_wrapp').find('.counter_block').show();
		$('.to-cart[data-item='+id+']').closest('.button_block').removeClass('wide');
		$('.wish_item[data-item='+id+']').addClass("added");
		$('.wish_item[data-item='+id+'] .value:not(.added)').hide();
		// $('.wish_item[data-item='+id+'] .value.added').show();
		$('.wish_item[data-item='+id+'] .value.added').css('display','inline');
	}
}

if(!funcDefined('basketFly')){ 
	var basketFly = function basketFly(action){		
		$.post( arKShopOptions['SITE_DIR']+"ajax/reload_basket_fly.php", "PARAMS="+$("#basket_form").find("input#fly_basket_params").val(), $.proxy(function( data ){									
			var small=$('.opener .basket_count').hasClass('small'),
				basket_count=$(data).find('.basket_count').find('.count').text(),
				wish_count=$(data).find('.wish_count').find('.count').text(); 
			$('#basket_line .basket_fly').html(data); 
			if(parseInt(basket_count)){
				$('#basket_line .basket_fly').removeClass('basket_empty');
			}
			if (action=='open') {
				if(small){
					$('.opener .basket_count').click();					
				}else{
					$('.opener .basket_count').removeClass('small')
					$('.tabs_content.basket li[item-section="AnDelCanBuy"]').addClass('cur');
					$('#basket_line ul.tabs li[item-section="AnDelCanBuy"]').addClass('cur');
				}
			} else if (action=='wish') {
				if(small){
					$('.opener .wish_count').click();					
				}else{
					$('.opener .wish_count').removeClass('small')
					$('.tabs_content.basket li[item-section="DelDelCanBuy"]').addClass('cur');
					$('#basket_line ul.tabs li[item-section="DelDelCanBuy"]').addClass('cur');
				}
			} else {
				$('.opener .basket_count').click();								
			}			

		}));
	}
}

if(!funcDefined("isRealValue")){
	function isRealValue(obj){
		return obj && obj !== "null" && obj!== "undefined";
	}
}

if(!funcDefined('reloadTopBasket')){ 
	var reloadTopBasket = function reloadTopBasket(action, basketWindow, popupWindow, delay, click, item){
		var obj={
				"PARAMS": $('#top_basket_params').val(),
				"ACTION": action
			};
		
		if(typeof item !== "undefined" ){
			obj.delete_top_item='Y';
			obj.delete_top_item_id=item.data('id');
		}
		setTimeout(function(){
			if(click !== "Y" ){
				$.post( arKShopOptions['SITE_DIR']+"ajax/show_basket_popup.php", obj, $.proxy(function( data ){	
					$(basketWindow).html(data);
					if(typeof popupWindow !== "undefined" ){
						$.post( arKShopOptions['SITE_DIR']+"ajax/show_basket_popup_modal.php", obj, $.proxy(function( data ){						
							$(popupWindow).html(data);				
						}))
					}
				}))
			}else{
				if(typeof popupWindow !== "undefined" ){
					$.post( arKShopOptions['SITE_DIR']+"ajax/show_basket_popup_modal.php", obj, $.proxy(function( data ){
						$(popupWindow).html(data);				
					}))
				}
			}
			if(typeof popupWindow !== "undefined" ){
				setTimeout(function(){
					var popupWidth = $(popupWindow).width();
					var popupHeight = $(popupWindow).height();
					$(popupWindow).css({
						'margin-left': '-' + popupWidth / 2 + 'px',
						'display': 'block',
						'top': $(document).scrollTop() + (($(window).height() > popupHeight ? ($(window).height() - popupHeight) / 2 : 10))   + 'px'
					})
				}, 500);
			}
		}, delay);
	}
}

if(!funcDefined("InitOrderCustom")){
	InitOrderCustom = function () {
		$('.ps_logo img').wrap('<div class="image"></div>');
		
		$('#bx-soa-order .radio-inline').each(function() {
			if ($(this).find('input').attr('checked') == 'checked') {
				$(this).addClass('checked');
			}
		});

		$('#bx-soa-order .checkbox input[type=checkbox]').each(function() {
			if ($(this).attr('checked') == 'checked')
				$(this).parent().addClass('checked');
		});

		$('#bx-soa-order .bx-authform-starrequired').each(function() {
			var html = $(this).html();
			$(this).closest('label').append('<span class="bx-authform-starrequired"> '+ html + '</span>');
			$(this).detach();
		});
		$('.bx_ordercart_coupon').each(function() {
			if ($(this).find('.bad').length)
				$(this).addClass('bad');
			else if ($(this).find('.good').length)
				$(this).addClass('good');
		});
		if (typeof(propsMap) !== 'undefined') {
			$(propsMap).on('click', function () {
				var value = $('#orderDescription').val();
				if ($('#orderDescription')) {
					if (value != '') {
						$('#orderDescription').closest('.form-group').addClass('value_y');
					}
				}
			});
		}
	}
}

if(!funcDefined("InitLabelAnimation")){
	InitLabelAnimation = function(className) {
		// Fix order labels
		if (!$(className).length) {
			return;
		}
		$(className).find('.form-group').each(function() {
			if ($(this).find('input[type=text], textarea').length && !$(this).find('.dropdown-block').length && $(this).find('input[type=text], textarea').val() != '') {
				$(this).addClass('value_y');
			}
		});

		$(document).on('click', className+' .form-group:not(.bx-soa-pp-field) label', function() {
			$(this).parent().find('input, textarea').focus();
		});

		$(document).on('focusout', className+' .form-group:not(.bx-soa-pp-field) input, '+className+' .form-group:not(.bx-soa-pp-field) textarea', function() {
			var value = $(this).val();
			if (value != '' && !$(this).closest('.form-group').find('.dropdown-block').length && !$(this).closest('.form-group').find('#profile_change').length) {
				$(this).closest('.form-group').addClass('value_y');
			}else{
				$(this).closest('.form-group').removeClass('value_y');
			}
		});

		$(document).on('focus', className+' .form-group:not(.bx-soa-pp-field) input, '+className+' .form-group:not(.bx-soa-pp-field) textarea', function() {
			if (!$(this).closest('.form-group').find('.dropdown-block').length && !$(this).closest('.form-group').find('#profile_change').length && !$(this).closest('.form-group').find('[name=PERSON_TYPE_OLD]').length ) {
				$(this).closest('.form-group').addClass('value_y');
			}
		});
	};
}


$.expr[':'].findContent = function(obj, index, meta){
	var matchParams = meta[3].split(',');
	regexFlags = 'ig';
	regex = new RegExp('^' + $.trim(matchParams) + '$', regexFlags);
	return regex.test($(obj).text());
};

$.fn.equalizeHeights = function(){
	var maxHeight = this.map(function(i, e) {
		return $(e).height();
	}).get();
	return this.height(Math.max.apply(this, maxHeight));
};

/*var isFunction = function(func){ 
	if(typeof func == 'function'){
		return true;
	}
	else{
		return false;
	}
}*/

if(!funcDefined('fRand')){
	var fRand = function(){
		return Math.floor(arguments.length > 1 ? (999999 - 0 + 1) * Math.random() + 0 : (0 + 1) * Math.random());
	}
}

if(!funcDefined('delSpaces')){ 
	var delSpaces = function delSpaces(str){
		str = str.replace(/\s/g, '');
		return str;
	}
}

if(!funcDefined('waitForFinalEvent')){
	var waitForFinalEvent = (function(){
		var timers = {};
		return function(callback, ms, uniqueId){
			if(!uniqueId){
				uniqueId = fRand();
			}
			if(timers[uniqueId]) {
				clearTimeout(timers[uniqueId]);
			}
			timers[uniqueId] = setTimeout(callback, ms);
		};
	})();
}

if(!funcDefined('onLoadjqm')){
	var onLoadjqm = function(name, hash, requestData, selector, requestTitle, isButton, thButton){
		hash.w.addClass('show').css({
			'margin-left': ($(window).width() > hash.w.outerWidth() ? '-' + hash.w.outerWidth() / 2 + 'px' : '-' + $(window).width() / 2 + 'px'),
			'top': $(document).scrollTop() + (($(window).height() > hash.w.outerHeight() ? ($(window).height() - hash.w.outerHeight()) / 2 : 10))   + 'px'
		});
		
		if(typeof(requestData) == 'undefined'){
			requestData = '';
		}
		if(typeof(selector) == 'undefined'){
			selector = false;
		}
		var width = $('.'+name+'_frame').width();		
		$('.'+name+'_frame').css('margin-left', '-'+width/2+'px');

		if(name=='order-popup-call')
		{
		}
		else if(name=='order-button')
		{
			$(".order-button_frame").find("div[product_name]").find("input").val(hash.t.title).attr("readonly", "readonly").css({"overflow": "hidden", "text-overflow": "ellipsis"});
		}
		else if(name=='basket_error')
		{
			$(".basket_error_frame .pop-up-title").text(requestTitle);
			$(".basket_error_frame .ajax_text").html(requestData);
			if(isButton=="Y" && thButton){
				$("<div class='popup_button_basket_wr'><span class='popup_button_basket button30' data-item="+thButton.data("item")+"><span>"+BX.message("ERROR_BASKET_BUTTON")+"</span></span></div>").insertAfter($(".basket_error_frame .ajax_text"));
			}
		}
		else if(name == "to-order" && selector)
		{
			$(".to-order_frame").find('[data-sid="PRODUCT_NAME"]').val($(selector).attr('alt')).attr("readonly", "readonly").css({"overflow": "hidden", "text-overflow": "ellipsis"});
			$(".to-order_frame").find('[data-sid="PRODUCT_ID"]').val($(selector).attr('data-item'));
		}
		else if( name == 'one_click_buy')
		{	
			$('#one_click_buy_form_button').on("click", function() {
				if(!$(this).hasClass("clicked")){
					if($('#one_click_buy_form').valid()){
						$(this).addClass("clicked");
						$("#one_click_buy_form").submit(); //otherwise don't works
					}
				}
			});
			// $('#one_click_buy_form_button').on("click", function() { $("#one_click_buy_form").submit(); });
			$('#one_click_buy_form').submit( function() 
			{
				if($('.'+name+'_frame form input.error').length || $('.'+name+'_frame form textarea.error').length) { return false }
				else
				{
					$.ajax({
						url: $(this).attr('action'),
						data: $(this).serialize(),
						type: 'POST',
						dataType: 'json',
						error: function(data) { alert('Error connecting server'); },
						success: function(data) 
						{							
							$("#one_click_buy_form_button").addClass("clicked");
							if(data.result=='Y') { $('.one_click_buy_result').show(); $('.one_click_buy_result_success').show(); } 
							else { $('.one_click_buy_result').show(); $('.one_click_buy_result_fail').show(); $('.one_click_buy_result_text').text(data.message);}
							$('.one_click_buy_modules_button', self).removeClass('disabled');
							$('#one_click_buy_form').hide();
							$('#one_click_buy_form_result').show();
							if($('.one_click_buy_result_success').is(':visible')){
								purchaseCounter(data.message, arKShopOptions["COUNTERS"]["TYPE"]["ONE_CLICK"]);
							}
						}
					});
				}
				return false;
			});
		}
		else if( name == 'one_click_buy_basket')
		{	
			$('#one_click_buy_form_button').on("click", function() {
				if(!$(this).hasClass("clicked")){
					if($('#one_click_buy_form').valid()){
						$(this).addClass("clicked");
						$("#one_click_buy_form").submit(); //otherwise don't works
					}
				}
			});
			// $('#one_click_buy_form_button').on("click", function() { $("#one_click_buy_form").submit(); }); //otherwise don't works
			$('#one_click_buy_form').on("submit", function() 
			{
				if($('.'+name+'_frame form input.error').length || $('.'+name+'_frame form textarea.error').length) { return false }
				else{
					$.ajax({
						url: $(this).attr('action'),
						data: $(this).serialize(),
						type: 'POST',
						dataType: 'json',
						error: function(data) { window.console&&console.log(data); },
						success: function(data) 
						{
							if(data.result=='Y') { $('.one_click_buy_result').show(); $('.one_click_buy_result_success').show(); } 
							else { $('.one_click_buy_result').show(); $('.one_click_buy_result_fail').show(); $('.one_click_buy_result_text').text(data.message);}
							$('.one_click_buy_modules_button', self).removeClass('disabled');
							$('#one_click_buy_form').hide();
							$('#one_click_buy_form_result').show();
							if($('.one_click_buy_result_success').is(':visible')){
								purchaseCounter(data.message, arKShopOptions["COUNTERS"]["TYPE"]["QUICK_ORDER"]);
							}
						}
					});
				}
				return false;
			});
		}
		
		$('.'+name+'_frame').show();
	}
}

if(!funcDefined('onHidejqm')){
	var onHidejqm = function(name, hash){
		if(hash.w.find('.one_click_buy_result_success').is(':visible') && name=="one_click_buy_basket"){
			window.location.href = window.location.href;
		}
		hash.w.css('opacity', 0).hide();
		hash.w.empty();
		hash.o.remove();
		hash.w.removeClass('show');
	}
}

if(!funcDefined("oneClickBuy"))
{
	var oneClickBuy = function (elementID, iblockID, that)
	{	
		var props_item='';
		var buy_btn=$(that).closest('.buy_buttons_wrapp').find('.to-cart');
		name = 'one_click_buy';
		if(typeof(that) !== 'undefined'){
			elementQuantity = $(that).attr('data-quantity');
			if($(that).data("props")){
				props_item=$(that).data("props");
			}		
		}

		var tmp_props=$(that).data("props"),
			props='',
			part_props='',
			add_props='N',
			fill_prop={},
			iblockid = buy_btn.data('iblockid'),
			item = buy_btn.attr('data-item');

		if(tmp_props){
			props=tmp_props.split(";");
		}
		if(buy_btn.data("part_props")){
			part_props=buy_btn.data("part_props");
		}
		if(buy_btn.data("add_props")){
			add_props=buy_btn.data("add_props");
		}

		fill_prop=fillBasketPropsExt(buy_btn, 'prop', buy_btn.data('bakset_div'));
		fill_prop.iblockID=iblockid;
		fill_prop.part_props=part_props;
		fill_prop.add_props=add_props;
		fill_prop.props=JSON.stringify(props);
		fill_prop.item=item;
		fill_prop.ocb_item="Y";

		//if($(that).data('offers')=="Y"){
			$('body').find('.'+name+'_frame').remove();
			$('body').append('<div class="'+name+'_frame popup"></div>');
			$('.'+name+'_frame').jqm({trigger: '.'+name+'_frame.popup', onHide: function(hash) { onHidejqm(name,hash); }, toTop: false, onLoad: function( hash ){ onLoadjqm(name, hash ); }, ajax: arKShopOptions["SITE_DIR"]+'ajax/one_click_buy.php?ELEMENT_ID='+elementID+'&IBLOCK_ID='+iblockID+'&ELEMENT_QUANTITY='+elementQuantity+'&OFFER_PROPS='+fill_prop.props});
			$('.'+name+'_frame.popup').click();	
		/*}else{
			if(buy_btn.data("empty_props")=="N"){
				showBasketError($("#"+buy_btn.data("bakset_div")).html(), BX.message("ERROR_BASKET_PROP_TITLE"), "Y", buy_btn);
			}else{
				$.ajax({
					type:"POST",
					url:arKShopOptions['SITE_DIR']+"ajax/check_propitem.php",
					data:fill_prop,
					dataType:"json",
					success:function(data){
						if("STATUS" in data){
							if(data.STATUS === 'OK'){
								$('body').find('.'+name+'_frame').remove();
								$('body').append('<div class="'+name+'_frame popup"></div>');
								$('.'+name+'_frame').jqm({trigger: '.'+name+'_frame.popup', onHide: function(hash) { onHidejqm(name,hash); }, toTop: false, onLoad: function( hash ){ onLoadjqm(name, hash ); }, ajax: arKShopOptions["SITE_DIR"]+'ajax/one_click_buy.php?ELEMENT_ID='+elementID+'&IBLOCK_ID='+iblockID+'&ELEMENT_QUANTITY='+elementQuantity+'&OFFER_PROPS='+fill_prop.props});
								$('.'+name+'_frame.popup').click();	
							}else{
								showBasketError(BX.message(data.MESSAGE));
							}
						}else{
							showBasketError(BX.message("CATALOG_PARTIAL_BASKET_PROPERTIES_ERROR"));
						}
					}
				})
			}
		}*/
		
	}
}

if(!funcDefined("oneClickBuyBasket"))
{
	var oneClickBuyBasket = function ()
	{	
		name = 'one_click_buy_basket'
		$('body').find('.'+name+'_frame').remove();
		$('body').append('<div class="'+name+'_frame popup"></div>');
		$('.'+name+'_frame').jqm({trigger: '.'+name+'_frame.popup', onHide: function(hash) { onHidejqm(name,hash); }, onLoad: function( hash ){ onLoadjqm( name, hash ); }, ajax: arKShopOptions["SITE_DIR"]+'ajax/one_click_buy_basket.php'});
		$('.'+name+'_frame.popup').click();	
	}
}


if(!funcDefined("jqmEd"))
{
	var jqmEd = function (name, form_id, open_trigger, requestData, selector, requestTitle, isButton, thButton)
	{
		if(typeof(requestData) == "undefined"){
			requestData = '';
		}
		if(typeof(selector) == "undefined"){
			selector = false;
		}
		$('body').find('.'+name+'_frame').remove();
		$('body').append('<div class="'+name+'_frame popup"></div>');
		if(typeof open_trigger == "undefined" )
		{
			$('.'+name+'_frame').jqm({trigger: '.'+name+'_frame.popup', onLoad: function( hash ){ onLoadjqm( name , hash , requestData, selector); }, ajax: arKShopOptions["SITE_DIR"]+'ajax/form.php?form_id='+form_id+(requestData.length ? '&' + requestData : '')});
		}
		else
		{
			if(name == 'enter'){
				$('.'+name+'_frame').jqm({trigger: open_trigger,  onLoad: function( hash ){ onLoadjqm( name , hash , requestData, selector); }, ajax: arKShopOptions["SITE_DIR"]+'ajax/auth.php'});
			}else if(name=='basket_error'){
				$('.'+name+'_frame').jqm({trigger: open_trigger,  onLoad: function( hash ){ onLoadjqm( name , hash , requestData, selector, requestTitle, isButton, thButton); }, ajax: arKShopOptions["SITE_DIR"]+'ajax/basket_error.php'});
			}
			else{
				$('.'+name+'_frame').jqm({trigger: open_trigger,  onLoad: function( hash ){ onLoadjqm( name , hash , requestData, selector); }, ajax: arKShopOptions["SITE_DIR"]+'ajax/form.php?form_id='+form_id+(requestData.length ? '&' + requestData : '')});
			}
			$(open_trigger).dblclick(function(){return false;})
		}	
		return true;		
	}
}
	
if(!funcDefined("replaceBasketPopup")){
	function replaceBasketPopup (hash){
		if(typeof hash != "undefined"){
			hash.w.hide();
			hash.o.hide();
		}
	}
}

if(!funcDefined("checkCounters")){
	function checkCounters(name){
		if(typeof name !== "undefined"){
			if(name=="google" && (arKShopOptions["COUNTERS"]["GOOGLE_ECOMERCE"]=="Y" && arKShopOptions["COUNTERS"]["GOOGLE_COUNTER"]>0)){
				return true;
			}else if(name=="yandex" && (arKShopOptions["COUNTERS"]["YANDEX_ECOMERCE"]=="Y" && arKShopOptions["COUNTERS"]["YANDEX_COUNTER"]>0)){
				return true;
			}else{
				return false;
			}
		}else if((arKShopOptions["COUNTERS"]["YANDEX_ECOMERCE"]=="Y" && arKShopOptions["COUNTERS"]["YANDEX_COUNTER"]>0) || (arKShopOptions["COUNTERS"]["GOOGLE_ECOMERCE"]=="Y" && arKShopOptions["COUNTERS"]["GOOGLE_COUNTER"]>0)) {
			return true;
		}else{
			return false;
		}
	}
}

if(!funcDefined("waitLayer")){
	function waitLayer(delay, callback){
		if((typeof dataLayer !== 'undefined') && (typeof callback === 'function')){
			callback();
		}
		else{
			setTimeout(function() {
				waitLayer(delay, callback);
			}, delay);
		}
	}
}

if(!funcDefined("addBasketCounter")){
	function addBasketCounter(id){
		if(checkCounters()) {			
			$.ajax({
				url:arKShopOptions['SITE_DIR']+"ajax/goals.php",
				dataType: "json",
				type: "POST",
				data: { "ID": id },
				success: function(item){
					if(item.ID){
						waitLayer(100, function(){
							//yandex
							dataLayer.push({
								"event": arKShopOptions["COUNTERS"]['GOOGLE_EVENTS']['ADD2BASKET'],
							    "ecommerce": {
							    	"currencyCode": item.CURRENCY,
							        "add": {
							            "products": [
							                {
							                    "id": item.ID,
							                    "name": item.NAME,
							                    "price": item.PRICE,
							                    "brand": item.BRAND,
							                    "category": item.CATEGORY,
							                    "quantity": item.QUANTITY
							                }
							            ]
							        }
							    }
							});
						});
					}
				}

			})
		}
	}
}

if(!funcDefined("purchaseCounter")){
	function purchaseCounter(order_id, type){
		if(checkCounters()) {
			$.ajax({
				url:arKShopOptions['SITE_DIR']+"ajax/goals.php",
				dataType: "json",
				type: "POST",
				data: { "ORDER_ID": order_id, "TYPE": type },
				success: function(order){
					var products=[];
					if(order.ITEMS){
						for(var i in order.ITEMS){
							products.push({
								"id": order.ITEMS[i].ID,
								"sku": order.ITEMS[i].ID,
			                    "name": order.ITEMS[i].NAME,
			                    "price": order.ITEMS[i].PRICE,
			                    "brand": order.ITEMS[i].BRAND,
			                    "category": order.ITEMS[i].CATEGORY,
			                    "quantity": order.ITEMS[i].QUANTITY
							})
						}
					}
					if(order.ID){
						waitLayer(100, function(){
							dataLayer.push({
							    "ecommerce": {
							    	"purchase": {
								    	"actionField":{
								    		"id": order.ACCOUNT_NUMBER,
								    		"shipping": order.PRICE_DELIVERY,
								    		"tax": order.TAX_VALUE,
								    		"list": type,
								    		"revenue": order.PRICE
								    	},
							            "products": products
							        }
							    }
							});
						});
					}
				}

			})
		}
	}
}

if(!funcDefined("viewItemCounter")){
	function viewItemCounter(id, price_id){
		$.ajax({
			url:arKShopOptions['SITE_DIR']+"ajax/goals.php",
			dataType: "json",
			type: "POST",
			data: { "PRODUCT_ID": id, "PRICE_ID": price_id },
			success: function(item){
				if(item.ID){
					waitLayer(100, function(){
						dataLayer.push({
							//"event": "",
							"ecommerce": {
								"detail": {
									"products": [
										{
											"id": item.ID,
											"name": item.NAME,
											"price": item.PRICE,
											"brand": item.BRAND,
											"category": item.CATEGORY
										}
									]
								}
							}
						});
					});
				}
			}
		})
	}
}

if(!funcDefined("checkoutCounter")){
	function checkoutCounter(step, option, url){
		if(checkCounters('google')) {
			$.ajax({
				url:arKShopOptions['SITE_DIR']+"ajax/goals.php",
				dataType: "json",
				type: "POST",
				data: { "BASKET": "Y" },
				success: function(basket){
					var products=[];
					if(basket.ITEMS){
						for(var i in basket.ITEMS){
							products.push({
								"id": basket.ITEMS[i].ID,
			                    "name": basket.ITEMS[i].NAME,
			                    "price": basket.ITEMS[i].PRICE,
			                    "brand": basket.ITEMS[i].BRAND,
			                    "category": basket.ITEMS[i].CATEGORY,
			                    "quantity": basket.ITEMS[i].QUANTITY
							})
						}
					}
					if(products){
						waitLayer(100, function(){
							dataLayer.push({
								"event": arKShopOptions["COUNTERS"]['GOOGLE_EVENTS']['CHECKOUT_ORDER'],
							    "ecommerce": {
							    	"actionField":{
							    		"step": step,
							    		"option": option
							    	},
							        "products": products
							    },
							    /*"eventCallback": function() {
							    	document.location = url;
							   }*/
							});
						});
					}
				}

			})
		}
	}
}

if(!funcDefined("delFromBasketCounter")){
	function delFromBasketCounter(id, callback){
		if(checkCounters()){
			$.ajax({
				url:arKShopOptions['SITE_DIR']+"ajax/goals.php",
				dataType: "json",
				type: "POST",
				data: { "ID": id },
				success: function(item){
					if(item.ID){
						waitLayer(100, function(){
							dataLayer.push({
								"event": arKShopOptions["COUNTERS"]['GOOGLE_EVENTS']['REMOVE_BASKET'],
							    "ecommerce": {
							        "remove": {
							            "products": [
							                {
							                    "id": item.ID,
							                    "name": item.NAME,
							                    "category": item.CATEGORY
							                }
							            ]
							        }
							    }
							});
							if(typeof callback == 'function'){
								callback();
							}
						});
					}
				}

			})
		}
	}
}

if(!funcDefined("createTableCompare")){
	function createTableCompare(originalTable, appendDiv){
		var clone = originalTable.clone().removeAttr('id').addClass('clone');
		appendDiv.append(clone);
	}
}

if(!funcDefined("initSly")){
	function initSly(){
		var $frame  = $(document).find('.frame');
		var $slidee = $frame.children('ul').eq(0);
		var $wrap   = $frame.parent();
		
		$frame.sly({
			horizontal: 1,
			itemNav: 'basic',
			smart: 1,			
			mouseDragging: 0,
			touchDragging: 1,
			releaseSwing: 0,
			startAt: 0,
			scrollBar: $wrap.find('.scrollbar'),
			scrollBy: 1,
			speed: 300,
			elasticBounds: 0,
			easing: 'swing',
			dragHandle: 1,
			dynamicHandle: 1,
			clickBar: 1,

			// Buttons
			forward: $wrap.find('.forward'),
			backward: $wrap.find('.backward'),
		});
		$frame.sly('reload');
	}
}

if(!funcDefined('fillBasketPropsExt')){
	fillBasketPropsExt = function(that, prop_code, basket_prop_div){
		var
			i = 0,
			propCollection = null,
			foundValues = false,
			basketParams = {},
			obBasketProps = null;
	
		// obBasketProps = that.closest('.catalog_detail').find('.basket_props_block');
		obBasketProps = BX(basket_prop_div);

		if (!!obBasketProps)
		{
			propCollection = obBasketProps.getElementsByTagName('select');
			if (!!propCollection && !!propCollection.length)
			{
				for (i = 0; i < propCollection.length; i++)
				{
					if (!propCollection[i].disabled)
					{
						switch(propCollection[i].type.toLowerCase())
						{
							case 'select-one':
								basketParams[propCollection[i].name] = propCollection[i].value;
								foundValues = true;
								break;
							default:
								break;
						}
					}
				}
			}
			propCollection = obBasketProps.getElementsByTagName('input');

			if (!!propCollection && !!propCollection.length)
			{
				for (i = 0; i < propCollection.length; i++)
				{
					if (!propCollection[i].disabled)
					{
						switch(propCollection[i].type.toLowerCase())
						{
							case 'hidden':
								basketParams[propCollection[i].name] = propCollection[i].value;
								foundValues = true;
								break;
							case 'radio':
								if (propCollection[i].checked)
								{
									basketParams[propCollection[i].name] = propCollection[i].value;
									foundValues = true;
								}
								break;
							default:
								break;
						}
					}
				}

			}
		}
		if (!foundValues)
		{
			basketParams[prop_code] = [];
			basketParams[prop_code][0] = 0;
		}
		return basketParams;
	}
}
if(!funcDefined('showBasketError')){
	showBasketError = function(mess, title, addButton, th){
		var title_set=(title ? title : BX.message("ERROR_BASKET_TITLE")),
			isButton="N",
			thButton="";
		if(typeof addButton!==undefined){
			isButton="Y";
		}
		if(typeof th!==undefined){
			thButton=th;
		}
		$("body").append("<span class='add-error-bakset' style='display:none;'></span>");
		jqmEd('basket_error', 'error-bakset', '.add-error-bakset', mess, this, title_set, isButton, thButton);
		$("body .add-error-bakset").click();
		$("body .add-error-bakset").remove();
	}
}

$(document).ready(function(){
	//some adaptive hacks
	$(window).resize(function(){
		waitForFinalEvent(function(){
			if($(window).outerWidth()>600 && $(window).outerWidth()<768 && $(".catalog_detail .buy_buttons_wrapp a").length>1) 
			{ 
				var adapt = false;
				var prev;
				$(".catalog_detail .buy_buttons_wrapp a").each(function(i, element)
				{
					prev = $(".catalog_detail .buy_buttons_wrapp a:eq("+(i-1)+")");
					if($(this).offset().top!=$(prev).offset().top && i>0) { $(".catalog_detail .buttons_block").addClass("adaptive"); }
				});
			} else { $(".catalog_detail .buttons_block").removeClass("adaptive"); }			
			
			if($(window).outerWidth()>600)
			{		
				$("#header ul.menu").removeClass("opened").css("display", "");
				
				if($(".authorization-cols").length)
				{
					$('.authorization-cols').equalize({children: '.col .auth-title', reset: true}); 
					$('.authorization-cols').equalize({children: '.col .form-block', reset: true}); 
				}
			}
			else
			{
				$('.authorization-cols .auth-title').css("height", "");
				$('.authorization-cols .form-block').css("height", "");
			}
			
			
			if($(window).width()>=400)
			{
				var textWrapper = $(".catalog_block .catalog_item .item-title").height();
				var textContent = $(".catalog_block .catalog_item .item-title a");
				$(textContent).each(function()
				{
					if($(this).outerHeight()>textWrapper) 
					{
						$(this).text(function (index, text) { return text.replace(/\W*\s(\S)*$/, '...'); });
					}
				});	
				$('.catalog_block').equalize({children: '.catalog_item .cost', reset: true}); 
				$('.catalog_block').equalize({children: '.catalog_item .item-title', reset: true}); 
				$('.catalog_block').equalize({children: '.catalog_item', reset: true}); 
			}
			else
			{
				$(".catalog_block .catalog_item").removeAttr("style");
				$(".catalog_block .catalog_item .item-title").removeAttr("style");
				$(".catalog_block .catalog_item .cost").removeAttr("style");
			}
			
			if($("#basket_form").length && $(window).outerWidth()<=600)
			{
				$("#basket_form .tabs_content.basket li.cur td").each(function() { $(this).css("width","");});
			}
			
			
			if($("#header .catalog_menu").length && $("#header .catalog_menu").css("display")!="none")
			{
				if($(window).outerWidth()>600)
				{
					//@todo функция нигде не определена
					//reCalculateMenu();
				}
			}
			
			if($(".front_slider_wrapp").length)
			{
				$(".extended_pagination li i").each(function() 
				{ 
					$(this).css({"borderBottomWidth": ($(this).parent("li").outerHeight()/2), "borderTopWidth": ($(this).parent("li").outerHeight()/2)}); 
				});
			}
			
		}, 
		50, fRand());
	});
	
	$('.avtorization-call.enter').live('click', function(e){
		e.preventDefault();
		$("body").append("<span class='evb-enter' style='display:none;'></span>");
		jqmEd('enter', 'auth', '.evb-enter', '', this);
		$("body .evb-enter").click();		
		$("body .evb-enter").remove();
	});

	$('.to-order').live('click', function(e){
		e.preventDefault();
		$("body").append("<span class='evb-toorder' style='display:none;'></span>");
		jqmEd('to-order', arKShopOptions['FORM']['TOORDER_FORM_ID'], '.evb-toorder', '', this);
		$("body .evb-toorder").click();		
		$("body .evb-toorder").remove();
	});
	
	$('.counter_block input[type=text]').numeric({allow:"."});
	
	$(".counter_block:not(.basket) .plus").live("click", function(){
		if($(this).parent().data("offers")!="Y"){
			var input = $(this).parents(".counter_block").find("input[type=text]")
				tmp_ratio = $(this).parents(".counter_wrapp").find(".to-cart").data('ratio'),
				isDblQuantity = $(this).parents(".counter_wrapp").find(".to-cart").data('float_ratio'),
				ratio=( isDblQuantity ? parseFloat(tmp_ratio) : parseInt(tmp_ratio, 10)),
				max_value='';
				currentValue = input.val();
			
			if(isDblQuantity)
				ratio = Math.round(ratio*arKShopOptions.JS_ITEM_CLICK.precisionFactor)/arKShopOptions.JS_ITEM_CLICK.precisionFactor;

			curValue = (isDblQuantity ? parseFloat(currentValue) : parseInt(currentValue, 10));

			curValue += ratio;
			if (isDblQuantity){
				curValue = Math.round(curValue*arKShopOptions.JS_ITEM_CLICK.precisionFactor)/arKShopOptions.JS_ITEM_CLICK.precisionFactor;
			}
			if(parseFloat($(this).data('max'))>0){
				if(input.val() < $(this).data('max')){
					if(curValue>$(this).data('max')){
						input.val($(this).data('max'));
					}else{
						input.val(curValue);
					}
					input.change();
				}
			}else{
				input.val(curValue);
				input.change();
			}
		}
	});
	
	$(".counter_block:not(.basket) .minus").live("click", function(){
		if($(this).parent().data("offers")!="Y"){
			var input = $(this).parents(".counter_block").find("input[type=text]"),
				tmp_ratio = $(this).parents(".counter_wrapp").find(".to-cart").data('ratio'),
				currentValue = parseFloat(input.val()),
				isDblQuantity = $(this).parents(".counter_wrapp").find(".to-cart").data('float_ratio'),
				ratio=( isDblQuantity ? parseFloat(tmp_ratio) : parseInt(tmp_ratio, 10)),
				max_value='';
				currentValue = input.val();

			if(isDblQuantity)
				ratio = Math.round(ratio*arKShopOptions.JS_ITEM_CLICK.precisionFactor)/arKShopOptions.JS_ITEM_CLICK.precisionFactor;

			curValue = (isDblQuantity ? parseFloat(currentValue) : parseInt(currentValue, 10));

			curValue -= ratio;
			if (isDblQuantity){
				curValue = Math.round(curValue*arKShopOptions.JS_ITEM_CLICK.precisionFactor)/arKShopOptions.JS_ITEM_CLICK.precisionFactor;
			}

			if(parseFloat($(this).parents(".counter_block").find(".plus").data('max'))>0){
				if(currentValue > ratio){ 
					if(curValue<ratio){
						input.val(ratio);
					}else{
						input.val(curValue);
					}
					input.change();
				}
			}else{
				if(curValue > ratio){ 
					input.val(curValue);
				}else{
					if(ratio){
						input.val(ratio);
					}else if(currentValue > 1){ 
						input.val(curValue);
					}
				}
				input.change();
			}
		}
	});
	$('.counter_block input[type=text]').live('change', function(e){
		var val = $(this).val(),
			tmp_ratio = $(this).parents(".counter_wrapp").find(".to-cart").data('ratio'),
			isDblQuantity = $(this).parents(".counter_wrapp").find(".to-cart").data('float_ratio'),
			ratio=( isDblQuantity ? parseFloat(tmp_ratio) : parseInt(tmp_ratio, 10));

		if(isDblQuantity)
			ratio = Math.round(ratio*arKShopOptions.JS_ITEM_CLICK.precisionFactor)/arKShopOptions.JS_ITEM_CLICK.precisionFactor;

		if(parseFloat($(this).parents(".counter_block").find(".plus").data('max'))>0){
			if(val>parseFloat($(this).parents(".counter_block").find(".plus").data('max'))){
				val=parseFloat($(this).parents(".counter_block").find(".plus").data('max'));
			}else if(val<ratio){
				val=ratio;
			}
		}else if(!parseFloat(val)){
			val=1;
		}
		$(this).parents('.counter_block').parent().parent().find('.to-cart').attr('data-quantity', val);		
		$(this).parents('.counter_block').parent().parent().find('.one_click').attr('data-quantity', val);	
		$(this).val(val);
	});

	$(document).on('click', '.popup_button_basket', function(){
		var th=$(".to-cart[data-item="+$(this).data("item")+"]");
		var val = th.attr('data-quantity');
		if(!val) $val = 1;

		var tmp_props=th.data("props"),
			props='',
			part_props='',
			add_props='N',
			fill_prop={},
			iblockid = th.data('iblockid'),
			offer = th.data('offers'),
			rid='',
			basket_props='',
			item = th.attr('data-item');

		if(offer!="Y"){
			offer = "N";
		}else{
			basket_props=th.closest('.prices_tab').find('.bx_sku_props input').val();
		}
		if(tmp_props){
			props=tmp_props.split(";");
		}
		if(th.data("part_props")){
			part_props=th.data("part_props");
		}
		if(th.data("add_props")){
			add_props=th.data("add_props");
		}
		if($('.rid_item').length){
			rid=$('.rid_item').data('rid');
		}else if(th.data('rid')){
			rid=th.data('rid');
		}
		
		fill_prop=fillBasketPropsExt(th, 'prop', 'bx_ajax_text');

		fill_prop.quantity=val;
		fill_prop.add_item='Y';	
		fill_prop.rid=rid;
		fill_prop.offers=offer;
		fill_prop.iblockID=iblockid;
		fill_prop.part_props=part_props;
		fill_prop.add_props=add_props;
		fill_prop.props=JSON.stringify(props);
		fill_prop.item=item;
		fill_prop.basket_props=basket_props;
		
		$.ajax({
			type:"POST",
			url:arKShopOptions['SITE_DIR']+"ajax/item.php",
			data:fill_prop,
			dataType:"json",
			success:function(data){
				$('.basket_error_frame').jqmHide();
				if("STATUS" in data){
					if(data.STATUS === 'OK'){

						th.hide();
						th.parent().find('.in-cart').show();

						addBasketCounter(item);
						$('.wish_item[data-item='+item+']').removeClass("added");
						$('.wish_item[data-item='+item+']').find(".value").show();
						$('.wish_item[data-item='+item+']').find(".value.added").hide();
						if($("#basket_line .basket_fly").length && $(window).outerWidth()>768){
							basketFly('open');
						}else if($("#basket_line .cart").length){
							if($("#basket_line .cart").is(".empty_cart")){
								$("#basket_line .cart").removeClass("empty_cart").find(".cart_wrapp a.basket_link").removeAttr("href").addClass("cart-call");
								$(".cart-call:not(.small)").click(function(){$('.card_popup_frame').jqmShow();}) //dirty hack, jqmAddTrigger doesn't work here, fix it
							}
							if($(window).outerWidth() > 520){
								if(arKShopOptions['THEME']['SHOW_BASKET_ONADDTOCART'] !== 'N'){
									$('.card_popup_frame').attr("animate", "true").jqmShow();
								}
							};
							reloadTopBasket('add', $('#basket_line'), $('.card_popup_frame'), 500);
						}
					}else{
						showBasketError(BX.message(data.MESSAGE));
					}
				}else{
					showBasketError(BX.message("CATALOG_PARTIAL_BASKET_PROPERTIES_ERROR"));
				}
			}
		})
	})

	$('.to-cart').live( 'click', function(e){
		e.preventDefault();		

		var th=$(this);
		var val = $(this).attr('data-quantity');
		if(!val) $val = 1;

		var tmp_props=$(this).data("props"),
			props='',
			part_props='',
			add_props='N',
			fill_prop={},
			iblockid = $(this).data('iblockid'),
			offer = $(this).data('offers'),
			rid='',
			basket_props='',
			item = $(this).attr('data-item');

		if(offer!="Y"){
			offer = "N";
		}else{
			basket_props=$(this).closest('.prices_tab').find('.bx_sku_props input').val();
		}
		if(tmp_props){
			props=tmp_props.split(";");
		}
		if($(this).data("part_props")){
			part_props=$(this).data("part_props");
		}
		if($(this).data("add_props")){
			add_props=$(this).data("add_props");
		}
		if($('.rid_item').length){
			rid=$('.rid_item').data('rid');
		}else if($(this).data('rid')){
			rid=$(this).data('rid');
		}
		
		fill_prop=fillBasketPropsExt(th, 'prop', th.data('bakset_div'));

		fill_prop.quantity=val;
		fill_prop.add_item='Y';	
		fill_prop.rid=rid;
		fill_prop.offers=offer;
		fill_prop.iblockID=iblockid;
		fill_prop.part_props=part_props;
		fill_prop.add_props=add_props;
		fill_prop.props=JSON.stringify(props);
		fill_prop.item=item;
		fill_prop.basket_props=basket_props;

		if(th.data("empty_props")=="N"){
			showBasketError($("#"+th.data("bakset_div")).html(), BX.message("ERROR_BASKET_PROP_TITLE"), "Y", th);
		}else{
			// $.get( arKShopOptions['SITE_DIR']+"ajax/item.php?item="+item+"&quantity="+val+"&rid="+rid+"&add_item=Y"+"&offers="+offer+"&iblockID="+iblockid+"&part_props="+part_props+"&add_props="+add_props+"&props="+JSON.stringify(props), 
			$.ajax({
				type:"POST",
				url:arKShopOptions['SITE_DIR']+"ajax/item.php",
				data:fill_prop,
				dataType:"json",
				success:function(data){
					if("STATUS" in data){
						if(data.STATUS === 'OK'){

							th.hide();
							th.parent().find('.in-cart').show();

							addBasketCounter(item);
							$('.wish_item[data-item='+item+']').removeClass("added");
							$('.wish_item[data-item='+item+']').find(".value").show();
							$('.wish_item[data-item='+item+']').find(".value.added").hide();
							if($("#basket_line .basket_fly").length && $(window).outerWidth()>768){
								basketFly('open');
							}else if($("#basket_line .cart").length){
								if($("#basket_line .cart").is(".empty_cart")){
									$("#basket_line .cart").removeClass("empty_cart").find(".cart_wrapp a.basket_link").removeAttr("href").addClass("cart-call");
									$(".cart-call:not(.small)").click(function(){$('.card_popup_frame').jqmShow();}) //dirty hack, jqmAddTrigger doesn't work here, fix it
								}
								if($(window).outerWidth() > 520){
									if(arKShopOptions['THEME']['SHOW_BASKET_ONADDTOCART'] !== 'N'){
										$('.card_popup_frame').attr("animate", "true").jqmShow();
									}
								};
								reloadTopBasket('add', $('#basket_line'), $('.card_popup_frame'), 500);
							}
						}else{
							showBasketError(BX.message(data.MESSAGE));
						}
					}else{
						showBasketError(BX.message("CATALOG_PARTIAL_BASKET_PROPERTIES_ERROR"));
					}
				}
			})
		}
	})
	
	$('.to-subscribe').live('click', function(e){
		e.preventDefault();
		if($(this).is('.auth')){
			$('.avtorization-call.enter').click();
		}
		else{
			var item = $(this).attr('data-item');
			$(this).hide();
			$(this).parent().find('.in-subscribe').show();	
			$.get(arKShopOptions['SITE_DIR'] + 'ajax/item.php?item=' + item + '&subscribe_item=Y',
				$.proxy(function(data){
				})
			);
		}
	})
	
	$('.in-subscribe').live('click', function(e){
		e.preventDefault();
		var item = $(this).attr('data-item');
		$(this).hide();
		$(this).parent().find('.to-subscribe').show();	
		$.get(arKShopOptions['SITE_DIR'] + 'ajax/item.php?item=' + item + '&subscribe_item=Y',
			$.proxy(function(data){
			})
		);
	})
	
	$('.wish_item').live('click', function(e){
		e.preventDefault();
		var item = $(this).attr('data-item');
		$(this).toggleClass('added');
		if($(this).find('.value.added').length){ 
			if(!$(this).find('.value.added').is(':visible')){
				$(this).find('.value').hide();
				$(this).find('.value.added').show();
			}else{
				$(this).find('.value').show();
				$(this).find('.value.added').hide();
			}
		}
		$('.basket_button.in-cart[data-item=' + item + ']').hide();	
		$('.basket_button.to-cart[data-item=' + item + ']').show();
		if(!$(this).closest('.module-cart').size()){
			$.get(arKShopOptions['SITE_DIR'] + 'ajax/item.php?item=' + item + '&wish_item=Y', 
				$.proxy(function(data){
					if($('.basket_fly').size()){
						basketFly('wish');
					}else{
						reloadTopBasket('wish', $('#basket_line'),'', 100);
					}
				})
			);
		}
	})
	
	$('.compare_item').live('click', function(e){
		e.preventDefault();
		var item = $(this).attr('data-item');
		var iblockID = $(this).attr('data-iblock');
		$(this).toggleClass('added');
		if($(this).find('.value.added').length){ 
			if($(this).find('.value.added').css('display') == 'none'){
				$(this).find('.value').hide();
				$(this).find('.value.added').show();
			}
			else{
				$(this).find('.value').show(); $(this).find('.value.added').hide();
			}
		}
		
		$.get(arKShopOptions['SITE_DIR'] + 'ajax/item.php?item=' + item + '&compare_item=Y&iblock_id=' + iblockID, 
			$.proxy(function(data){
				jsAjaxUtil.InsertDataToNode(arKShopOptions['SITE_DIR'] + 'ajax/show_compare_preview.php', 'compare_small', false); 
			})
		);
	});

	$('.compare_frame').remove();
	$('body').append('<span class="compare_frame popup"></span>');
	$('.compare_frame').jqm({trigger: '.compare_link', onLoad: function(hash){onLoadjqm('compare', hash); }, ajax: arKShopOptions['SITE_DIR']+'ajax/show_compare_list.php'});
	
	$('.fancy').fancybox({
		openEffect  : 'fade',
		closeEffect : 'fade',
		nextEffect : 'fade',
		prevEffect : 'fade'
	});

	/*order new start*/
	InitLabelAnimation('#bx-soa-order-form');
	InitOrderCustom();
	BX.addCustomEvent(window, "onAjaxSuccess", function(){
		InitLabelAnimation('#bx-soa-order-form');
		InitOrderCustom();
	});
	BX.addCustomEvent(window, "onFrameDataRequestFail", function(response){
		console.log(response);
	});
	/*order new end*/

	$(document).on('change', '.sort_filter select', function(){
		var url=$(this).find('option:selected').data('href');
		location.href=url;
	})

	$(document).on('click', '.bx_compare .tabs-head li', function(){
		var url=$(this).find('.sortbutton').data('href');
		BX.showWait(BX("bx_catalog_compare_block"));
		$.ajax({
			url: url,
			data: {'ajax_action': 'Y'},
			success: function(html){
				history.pushState(null, null, url);
				$('#bx_catalog_compare_block').html(html);
				BX.closeWait();
			}
		})
	})
	var hoveredTrs;
	$(document).on({
		mouseover: function(e){
			var _ = $(this);
			var tbodyIndex = _.closest('tbody').index()+1; //+1 for nth-child
			var trIndex = _.index()+1; // +1 for nth-child
			hoveredTrs = $(e.delegateTarget).find('.data_table_props')
				.children(':nth-child('+tbodyIndex+')')
				.children(':nth-child('+trIndex+')').addClass('hovered');
		},
		mouseleave: function(e){
			if(hoveredTrs)
				hoveredTrs.removeClass('hovered');
		}
	}, '.bx_compare .data_table_props tbody>tr');
});