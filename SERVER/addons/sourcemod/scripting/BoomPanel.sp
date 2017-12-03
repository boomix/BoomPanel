#pragma semicolon 1
#define DEBUG
#pragma newdecls required

#include <sourcemod>
#include <clientprefs>
#include <geoip>
#include <cstrike>
#include <basecomm>


#include "BoomPanel/BP_Globals.sp"
#include "BoomPanel/BP_Functions.sp"
#include "BoomPanel/BP_Convars.sp"
#include "BoomPanel/BP_ServerID.sp"
#include "BoomPanel/BP_Players.sp"
#include "BoomPanel/BP_PlayersIP.sp"
#include "BoomPanel/BP_PlayersUsername.sp"
#include "BoomPanel/BP_PlayersOnline.sp"
#include "BoomPanel/BP_Chat.sp"
#include "BoomPanel/BP_Bans.sp"
#include "BoomPanel/BP_Admins.sp"
#include "BoomPanel/BP_MuteGag.sp"
#include "BoomPanel/BP_RconCommands.sp"
#include "BoomPanel/BP_Natives.sp"

#pragma newdecls required

//DB DEFINITIONS
/*
	pid = player ID
	sid = server ID
	aid = admin ID
*/

//Credits
//asherkin - help admin commands logging
//Credits to `11530` https://forums.alliedmods.net/showthread.php?t=183443


public Plugin myinfo = 
{
	name = "BoomPanel",
	author = "boomix",
	description = "BoomPanel admin panel",
	version = "1.00",
	url = "http://steamcommunity.com/id/boomix69/"
};

public void OnPluginStart()
{
	Database.Connect(SQLConnect, "BoomPanel");

	//Events
	HookEvent("player_disconnect", 	Event_Disconnect); 
	HookEvent("player_team",		Event_PlayerTeam); 
	
	//Command listeners
	AddCommandListener(OnPlayerChatMessage, 		"say");
	AddCommandListener(OnPlayerChatMessage, 		"say_team");
	AddCommandListener(OnAddBanCommand, 			"sm_addban");
	 
	AddCommandListener(OnPlayerMuteGag, 	"sm_mute");
	AddCommandListener(OnPlayerMuteGag, 	"sm_unmute");
	AddCommandListener(OnPlayerMuteGag, 	"sm_gag");
	AddCommandListener(OnPlayerMuteGag, 	"sm_ungag");
	AddCommandListener(OnPlayerMuteGag, 	"sm_silence");
	AddCommandListener(OnPlayerMuteGag,		"sm_unsilence");
	
	//Easier shortcuts
	RegAdminCmd("sm_pmute", 	CMD_PermaMuteGag, 	ADMFLAG_CHAT);
	RegAdminCmd("sm_pgag", 		CMD_PermaMuteGag, 	ADMFLAG_CHAT);
	RegAdminCmd("sm_psilence", 	CMD_PermaMuteGag, 	ADMFLAG_CHAT);

	
	//Other stuff
	hConnected = RegClientCookie("bp_connected", "When player connected to server", CookieAccess_Protected);
	BP_OnPluginStart();
	
}

bool IsSpectator(int client)
{
	if(GetClientTeam(client) == CS_TEAM_CT || GetClientTeam(client) == CS_TEAM_T)
		return false;
	else
		return true;
}

void Main_OnMapStart()
{
	if(DB == null) {
		
		if (SQL_CheckConfig("BoomPanel"))
			Database.Connect(SQLConnect, "BoomPanel");
		else
			Database.Connect(SQLConnect, "default");
			
	}
}

//***************
//--------------
//DATABASE CONNECT
//--------------
//***************

public void SQLConnect(Database db, const char[] error, any data)
{
	if (db == null)
	{
		delete db;
		LogError("Database failure: %s", error);	
		return;
	} 

	else 
	{
		DB = db;
		DB.Query(OnRowInserted, "SET NAMES \"UTF8\"");
		DB.SetCharset("utf8");
		OnDBConnected();
	}
}

public void OnRowInserted(Database db, DBResultSet results, const char[] error, any data)
{
	if(results == null)
	{
		LogError("[BOOMPANEL] MYSQL ERROR: %s", error);
		return;
	}	
}
