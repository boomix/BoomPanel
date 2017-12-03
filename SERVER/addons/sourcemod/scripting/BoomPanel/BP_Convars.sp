void BP_InitConVars()
{
	//Creates ton of cvars
	g_cvServerIP		= CreateConVar("bp_server_ip", 				"", 	"Set server IP by cvar if by default it got wrong server IP, leave empty if everything works fine");
	g_cvServerPORT		= CreateConVar("bp_server_port", 			"", 	"Set server PORT by cvar if by default it got wrong server PORT, leave empty if everything works fine");
	g_cvBansEnabled 	= CreateConVar("bp_bans_enabled", 			"1", 	"Turn on/off boompanel ban system", 0, true, 0.0, true, 1.0);
	g_cvAdminsEnabled 	= CreateConVar("bp_admins_enabled", 		"1", 	"Turn on/off boompanel admin system", 0, true, 0.0, true, 1.0);
	g_cvMuteGagEnabled 	= CreateConVar("bp_mutegag_enabled",		"1", 	"Turn on/off boompanel mute, gag system", 0, true, 0.0, true, 1.0);
	g_cvChatLogEnabled 	= CreateConVar("bp_chatlog_enabled", 		"1", 	"Turn on/off boompanel chat log system", 0, true, 0.0, true, 1.0);
	g_cvMuteGagAllSrvs	= CreateConVar("bp_mutegag_all_servers", 	"0", 	"If you mute/gag someone, should he be muted/gaged in all of your servers (0: only this servers | 1: all servers that are connected to panel)", 0, true, 0.0, true, 1.0);
	g_cvBansAllSrvs		= CreateConVar("bp_bans_all_servers", 		"0", 	"If you ban someone, should he be banned in all of your servers (0: only this servers | 1: all servers that are connected to panel)", 0, true, 0.0, true, 1.0);


	AutoExecConfig(true, "BoomPanel");

}