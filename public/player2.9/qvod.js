var QvodPlayer = '';
var bstartnextplay = false;
var browser = navigator.appName;
function $Showhtml(){
	Player.Url = $QvodUrl(Player.Url,Player.UrlName);
	Player.NextUrl = $QvodUrl(Player.NextUrl,Player.NextUrlName);
	return '<object classid="clsid:F3D0D36F-23F8-4682-A195-74C92B03D4AF" width="100%" height="'+Player.Height+'" id="QvodPlayerIe" name="QvodPlayerIe"><param name="URL" value="'+Player.Url+'"/><param name="QvodAdUrl" value=""/><param name="NextWebPage" value="'+Player.NextWebPage+'"><param name="Autoplay" value="1"/><embed type="application/qvod-plugin" id="QvodPlayerNt" name="QvodPlayerNt" URL="'+Player.Url+'" NextWebPage="'+Player.NextWebPage+'" width="100%" height="'+Player.Height+'" QvodAdUrl="" Autoplay="1"></embed></object>';
}
function $QvodUrl(url,urlname){
	if(url.indexOf("vod://")>0){
		onurl = url.split("|");
		//获取地址后缀
		var parts = onurl[2].split(".");
		if (parts.length > 1) {   
			suffix = parts.pop();
		}else{
			suffix = 'rmvb';
		}
		return(onurl[0]+"|"+onurl[1]+"|"+Player.VodName+urlname+"[www.hzr8.com]."+suffix+'|');
	}
	return(url);
}
function $QvodStatus(){
	if(QvodPlayer.Full == 0){
		if(QvodPlayer.PlayState == 3){
			$$('buffer').style.display = 'none';
		}else{
			$$('buffer').style.display = 'block';
		}
	}
}
function $QvodNextDown(){
	if(QvodPlayer.get_CurTaskProcess() > 900 && !bstartnextplay){
		QvodPlayer.StartNextDown(Player.NextUrl);
		bstartnextplay = true;
	}
}
function $QvodTask(){
	$$('buffer').height = Player.Height-44;
	setInterval("$QvodStatus()",588);
	if(Player.NextWebPage){
		setInterval("$QvodNextDown()",8888);
	}
}
function $QvodCheck(){
	try{
		QvodPlayer.CallFunction("ab");
		$QvodTask();
	}catch(e){
		Player.Install();
		return;
	}
}
if(browser == "Netscape"){
	Player.Show();
	QvodPlayer = document.getElementById("QvodPlayerNt");
	$QvodCheck();
}else if(browser == "Microsoft Internet Explorer"){
	Player.Show();
	QvodPlayer = document.getElementById("QvodPlayerIe");
	$QvodCheck();
}else{
	$$('js_play_left_player').innerHTML = '<h1>请使用IE内核浏览器观看本站影片!</h1>';
}