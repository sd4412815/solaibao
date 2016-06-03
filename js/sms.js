function trim(str) {
	return str.replace(/^\s*(.*?)[\s\n]*$/g, '$1');
}


function getverifycode()
{
	var mobile = document.getElementById('username').value;
	if(mobile == '') {
		alert("手机号不能为空！");
	}else
	{
		Ajax.call('sms.php?step=getverifycode&r=' + Math.random(), 'mobile=' + mobile, getverifycodeResponse, 'POST', 'JSON');
	}
}

function getverifycodeResponse(result)
{
	alert(result.message);
	if(result.error)
	{
		document.getElementById('code_notice').innerHTML = '';
	}
	else
	{
		document.getElementById('code_notice').innerHTML =120;
		document.getElementById('code_btn').disabled = true;
	}	
}

function run()
{
    var s = document.getElementById('code_notice');
    if(s.innerHTML == 0){
       document.getElementById('code_btn').disabled=0;
	   document.getElementById('code_notice').innerHTML = '';
       return false;
    }
    s.innerHTML = s.innerHTML * 1 - 1;
}

window.setInterval("run()",1000);
