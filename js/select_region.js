
     function get_regions(level, rid)
     {
		var obj_num = $('.childli').length;
		for(var i=1;i<=obj_num;i++){
			$('#city_li_'+i).attr('class','li');
			$('#city_box_'+i).hide();
			if(level == i){
				$('#city_li_'+i).attr('class','licur');
				$('#city_box_'+i).show();
			}
		}
		if(rid)
		{
			Ajax.call('region_city.php', 'act=get_regions&level='+level+'&rid=' + rid , get_regions_response, 'GET', 'JSON');
		}
     }
     function get_regions_response(result)
     {	
		 if(result.cookeinfo){
			 document.cookie=result.cookeinfo.key+"="+result.cookeinfo.value;
			 for(i=1;i<=4;i++){
				 if(i>=result.level){
					  document.cookie="region_"+i+"=0";
				 }
			 }
		 }
		 if(result.result == ''){
			 location.reload();
		 }
		 var obj_num = $('.childli').length;
		 for(var i=1;i<=obj_num;i++){
			 $('#city_box_'+i).hide();
			 $('#city_li_'+i).attr('class','li');
			 if(result.level == i && result.result!=''){
				$('#city_li_'+i).attr('class','licur').html('请选择').show();
				$('#city_box_'+i).html(result.result).show();
			 }else if(result.level < i){
				 $('#city_li_'+i).hide();
			 }
		 }
		 $('#city_li_'+(--result.level)).html(result.parent_name).show();
      }