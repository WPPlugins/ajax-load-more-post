if((typeof jQuery === 'undefined') && window.jQuery) {
	jQuery = window.jQuery;
} else if((typeof jQuery !== 'undefined') && !window.jQuery) {
	window.jQuery = jQuery;
}
var flg_v1 = 0; 
 
function APLM_loadMorePosts(limit,elementId,total,request_obj){
	if(flg_v1==1) return;
	jQuery(document).ready(function($){ 
			var root_element = $("#"+elementId); 
			$(root_element).find(".item-posts").find(".ik-post-load-more").html("<div align='center'>"+$(".wp-load-icon").html()+"</div>");
			flg_v1 = 1;
			$.ajax({
				url: ajaxpostloadmore.aplm_ajax_url, 
				data: {'action':'getMorePosts',security: ajaxpostloadmore.aplm_security,'limit_start' : limit,'total' : total,'hide_post_title' : request_obj.hide_post_title,'post_title_color' : request_obj.post_title_color,'header_text_color' : request_obj.header_text_color,'header_background_color' : request_obj.header_background_color,'display_title_over_image' : request_obj.display_title_over_image,'number_of_post_display' : request_obj.number_of_post_display,'vcode' : request_obj.vcode	},
				success:function(data) {     
					APLM_printData(elementId,data,"loadmore");
				},error: function(errorThrown){ console.log(errorThrown);}
			});
	});
} 
function APLM_printData(elementId,data,flg){
	jQuery(document).ready(function($){  
		
	  	var root_element = $("#"+elementId); 
		 
		if(flg=="loadmore"){
			$(root_element).find(".item-posts").find(".wp-load-icon").remove();
			$(root_element).find(".item-posts").find(".clr").remove();
			$(root_element).find(".item-posts").find(".ik-post-load-more").remove(); 
			$(root_element).find(".item-posts").append(data).fadeIn(400); 
			$(root_element).find(".item-posts").append("<div class='clr'></div>");
		}else{ 
			$("#"+elementId).find(".ik-load-content,.ik-post-no-items").remove(); 
			$(root_element).find(".item-posts").html(data).fadeIn(400);  
		}
		 
		flg_v1 = 0;	
	});	  
	aplm_manage_grid_layout(elementId);
}
var flg_ms_hover = 0;
function aplm_pr_item_image_mousehover(ob_pii){ 
	if(flg_ms_hover == 1) return;
	jQuery(document).ready(function($){
		$(ob_pii).find(".ov-layer").show();  
		$(ob_pii).find(".ov-layer").css("visibility","visible"); 
		$(ob_pii).find(".ov-layer").css("top","40");  
		flg_ms_hover = 1;
		if($.trim($(ob_pii).find(".ov-layer").html())!="")
			$(ob_pii).find(".ov-layer").animate({opacity:0.9,top:0},0); 
		else
			$(ob_pii).find(".ov-layer").animate({opacity:0.5,top:0},0); 
	});
} 
function aplm_pr_item_image_mouseout(ob_pii){
	jQuery(document).ready(function($){ 
		$(ob_pii).find(".ov-layer").animate({opacity:0,top:40},0);
		flg_ms_hover = 0;
		$(ob_pii).find(".ov-layer").hide();
		$(ob_pii).find(".ov-layer").css("visibility","hidden");  
	});
}

function aplm_cat_aplm_ms_out(ob_ms_eff){
	jQuery(document).ready(function($){ 
		$(ob_ms_eff).removeClass("pn-active-bg"); 	
	});
}
function aplm_cat_aplm_ms_hover(ob_ms_eff){
	jQuery(document).ready(function($){ 
		$(ob_ms_eff).addClass("pn-active-bg"); 	
	});
}



function aplm_manage_grid_layout( elementId ) {
 
	jQuery(document).ready(function($){
	
		var root_element = $("#"+elementId); 		
		
		if($("#"+elementId).parent().hasClass("pane_style_3")) { 
			
			var cnt_width_lt_six = $("#"+elementId).parent().width();
			var prod_item_height_lt_six = [];	 
			
			$(root_element).find(".item-posts").find(".ik-post-item").each(function(){		
				 
				if(cnt_width_lt_six > 1280)		
					$(this).css("width","305px");
				else if(cnt_width_lt_six <= 1280 && cnt_width_lt_six > 1024){	
					$(this).css("width","24%");
					$(this).find(".ik-post-content").css("width","70%");
					$(this).find(".ik-post-image").css("width","25%"); 
				}		
				else if(cnt_width_lt_six <= 1024 && cnt_width_lt_six > 700){	
					$(this).css("width","32%");
					$(this).find(".ik-post-content").css("width","70%");
					$(this).find(".ik-post-image").css("width","25%"); 
				}	
				else if(cnt_width_lt_six <= 700 && cnt_width_lt_six > 570){				
					$(this).css("width","49%");
					$(this).find(".ik-post-content").css("width","70%");
					$(this).find(".ik-post-image").css("width","25%"); 
				}
				else if(cnt_width_lt_six <= 570 && cnt_width_lt_six > 480){ 				
					$(this).css("width","49%");
					$(this).find(".ik-post-content").css("width","65%");
					$(this).find(".ik-post-image").css("width","30%"); 
				}
				else if(cnt_width_lt_six <= 479 ){ 				
					$(this).css("width","98%");
					$(this).find(".ik-post-content").css("width","65%");
					$(this).find(".ik-post-image").css("width","30%"); 
				}  
				 
				$(this).find(".ik-post-name").removeAttr( "style" );
				prod_item_height_lt_six.push( $(this).find(".ik-post-name").height() );  
				
			}); 
			 
			$(root_element).find(".item-posts").find(".ik-post-item").find(".ik-post-name").css("height",(Math.max.apply(Math,prod_item_height_lt_six))+"px");
					
		} else {
		
				var cnt_width = $("#"+elementId).parent().width();
				var prod_item_height = [];	
		 
				$(root_element).find(".item-posts").find(".ik-post-item").each(function(){		
					
					if(cnt_width > 1280)		
						$(this).css("width","184px");
					else if(cnt_width <= 1280 && cnt_width > 1024)		
						$(this).css("width","15.5%");	
					else if(cnt_width <= 1024 && cnt_width > 768)	
						$(this).css("width","19%");
					else if(cnt_width <= 858 && cnt_width > 640)	
						$(this).css("width","24%");
					else if(cnt_width <= 640 && cnt_width > 480)	
						$(this).css("width","32%"); 
					else if(cnt_width <= 480 && cnt_width > 260)	
						$(this).css("width","49%");  
					else if(cnt_width <= 260)	
						$(this).css("width","99%");  	 
					
					$(this).find(".ik-post-name").removeAttr( "style" );	
					prod_item_height.push($(this).find(".ik-post-name").height());
					
				}); 
				
				$(root_element).find(".item-posts").find(".ik-post-item").find(".ik-post-name").css("height",(Math.max.apply(Math,prod_item_height))+"px");
		} 
		
	});	

}

function aplm_init_accordion(){
	jQuery(document).ready(function($){ 
		$(".wea_content .item-posts").each(function(){ 
			aplm_manage_grid_layout($(this).parent().attr("id"));
		});
		 
		$(window).resize(function(){
			$(".wea_content .item-posts").each(function(){ 
					aplm_manage_grid_layout($(this).parent().attr("id"));
			});
		});  
		
	});	
}	

if ( window.addEventListener ) { 
	window.addEventListener( "load", aplm_init_accordion, false );
}
else 
{    
	if ( window.attachEvent ) { 
		  window.attachEvent( "onload", aplm_init_accordion );
	} else {
		 if ( window.onLoad ) {
		   window.onload = aplm_init_accordion;
		 }
	}	 
}