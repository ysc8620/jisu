function $Showhtml(){
	return '<embed allowfullscreen="true" wmode="transparent" quality="high" src="http://www.iqiyi.com/player/20130812164748/Player.swf?flag=1&vid='+Player.Url+'" quality="high" bgcolor="#000" width="100%" height="'+Player.Height+'" name="player" id="playerr" align="middle" allowScriptAccess="always" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>';
}
Player.Show();
if(Player.Second){
	$$('buffer').style.height = Player.Height-28;
	$$("buffer").style.display = "block";
	setTimeout("Player.BufferHide();",Player.Second*1000);
}