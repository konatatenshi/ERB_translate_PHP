function onUpload(url) {
	if(confirm('确定要上传此翻译字典文件吗？如包含在此字典的翻译将被覆盖。')){
    let formData = new FormData();
	let passwords = $("#password").val();
            // 获取文件
            var fileData = $("#file1").prop("files")[0];
            formData.append("file1", fileData);
            $.ajax({
                url: 'translate.php?password='+passwords+'&upload=' + url,
                type: 'POST',
                async: false,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
					console.log(data);
                    alert('上传成功，请刷新浏览器重新加载文件。');
                }
            });
	}
}
		
//input变色
function check(e){
	if(event.keyCode==13){
		sv(e);
	}else{
		console.log(event.keyCode);
        $("#tr" + e).addClass("i");
	}
}
function dc(){
	let passwords = $("#password").val();
	var url = window.location.href + '&password='+passwords+'&down';
	console.log(url);
	window.location.href = url;
}
function tr(e){
	let value = $("#tr" +e).val()
	let searchRegExp = /%/g
	value=value.replace(searchRegExp,"％");
	$.ajax({
		url:'baidu.php',
		method:'GET',
		dataType:"JSON",
		data:{    
				 fy : value  
		},  
	  success:function(res){
		  console.log(res);
		  if(res.code==200){
			$("#tr" + e).attr({"value": res.txt});
			$("#tr" + e).addClass("i");
		  }else{
			  alert('过于频繁');
		  }
	  },
	  error:function(res){
			  alert(res);
	  },
	});
	
}

function sv(e){
	let yuuanwentxt = $("#yw" +e).val();
	let fanyitxt = $("#tr" +e).val();
	let passwords = $("#password").val();
	$.ajax({
		url:window.location.href,
		method:'GET',
		dataType:"JSON",
		data:{    
				save : 1 ,
				yuanwen : yuuanwentxt,
				fanyi : fanyitxt,
				password : passwords
		},  
	  success:function(res){
		  console.log(res);
		  if(res.code==200){
			$("#tr" + e).removeClass("i");
		  }else{
			  alert('过于频繁');
		  }
	  },
	  error:function(res){
			  alert(res);
	  },
	});
}