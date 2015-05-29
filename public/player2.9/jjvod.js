function $PlayerNt(){
	if (navigator.plugins) {
		var Install = false;
		for (i=0; i < navigator.plugins.length; i++ ) {
			if( navigator.plugins[i].name == 'JJvod Plugin'){
				Install = true; break;
			}
		}
		if(Install){
			return '<object id="jjvodPlayer" name="jjvodPlayer" TYPE="application/x-itst-activex" ALIGN="baseline" BORDER="0" WIDTH="'+Player.Width+'" HEIGHT="'+Player.Height+'" progid="WEBPLAYER.WebPlayerCtrl.2" param_URL="'+Player.Url+'" param_WEB_URL="'+unescape(window.location.href)+'"></object>';
		}
	}
	Player.Install();
}
function $PlayerIe(){
         player = '<object classid="clsid:C56A576C-CC4F-4414-8CB1-9AAC2F535837" width="'+Player.Width+'" height="'+Player.Height+'" id="jjvodPlayer" name="jjvodPlayer" onerror="Player.Install();"><PARAM NAME="URL" VALUE="'+Player.Url+'"><PARAM NAME="WEB_URL" VALUE="'+unescape(window.location.href)+'"><param name="Autoplay" value="1"></object>';
        return player;
}
function $jjvodstatus(){
    if(document.getElementById('jjvodPlayer').PlayState==3){
         $$('buffer').style.display = 'none';
    }else if(document.getElementById('jjvodPlayer').PlayState==2||document.getElementById('jjvodPlayer').PlayState==4){
         $$('buffer').style.display = 'block';
    }
}
function $Showhtml(){
	if(window.ActiveXObject || window.ActiveXObject !== undefined){
		return $PlayerIe();
	}else{
		return $PlayerNt();
	}
}
Player.Show();
$$('buffer').height = Player.Height-50;
setInterval('$jjvodstatus()','700');