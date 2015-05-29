function $Showhtml(){
	player = '<embed allowNetworking="internal" allowFullScreen="true" allowscriptaccess="never" src="http://www.tudou.com/a/oO9sKFzT3Gs/&resourceId=0_05_05_99&iid='+Player.Url+'&bid=05/v.swf&autoPlay=true" type="application/x-shockwave-flash" width="100%" height="'+Player.Height+'"></embed>';
	return player;
}
Player.Show();
Player.Show();
if(Player.Second){
	$$('buffer').style.height = Player.Height-20;
	$$("buffer").style.display = "block";
	setTimeout("Player.BufferHide();",Player.Second*1000);
}