void Bans_OnClientIDRecived(int client)
{
	if(g_cvBansEnabled.IntValue == 1 && DB != null && iServerID != -1) {
		//Check if player is banned
		char query[500];
		Format(query, sizeof(query), 
		"SELECT `reason`, `length`, `username`, TIMESTAMPDIFF(MINUTE, time, now()) AS time_passed "...
		"FROM bp_bans AS b LEFT JOIN bp_players_username AS pu ON b.aid = pu.pid "...
		"WHERE b.pid = %i AND unbanned = 0 AND (TIMESTAMPDIFF(MINUTE, time, now()) < b.length OR b.length = 0) AND (sid = %i OR sid = 0) "...
		"ORDER BY last_used DESC LIMIT 1", iClientID[client], iServerID);
		DB.Query(OnBanCheck, query, GetClientUserId(client), DBPrio_Normal);
	}
}

void Bans_OnMapStart()
{
	//Update hostname on each map start
	ConVar hostname = FindConVar("hostname");
	hostname.GetString(cServerHostName, sizeof(cServerHostName));
}

//On add ban command
public Action OnAddBanCommand(int client, const char[] command, int args)
{
	if(g_cvBansEnabled.IntValue == 1 && DB != null && iServerID != -1) {
		
		//Manually send this command to bp_chat, so it gets logged (because such command already exists in sourcemod)
		char cMessage[256], cEscMessage[256];
		GetCmdArgString(cMessage, sizeof(cMessage));
		DB.Escape(cMessage, cEscMessage, sizeof(cEscMessage));
		Format(cEscMessage, sizeof(cEscMessage), "%s %s", command, cEscMessage);
		LogChatMessage(client, cEscMessage, 1);
		
		//Replace original add ban command
		if(args < 2) {
			ReplyToCommand(client, "%s!addban <steamid> <time> [reason]", PREFIX);
			return Plugin_Stop;
		}
		
		//Convert steamid32 to steamid64
		char steamid[20], steamid64[20];
		GetCmdArg(1, steamid, sizeof(steamid));
		GetCommunityID(steamid, steamid64, sizeof(steamid64));
		if(StrEqual(steamid64, "")) {
			ReplyToCommand(client, "%sWrong steamID format", PREFIX);
			return Plugin_Stop;
		}
		
		char cReason[100], cLenght[10];
		GetCmdArg(2, cLenght, sizeof(cLenght));
		GetCmdArg(3, cReason, sizeof(cReason));
		int length = StringToInt(cLenght);
		
		//Kick player if he is ingamae
		for (int i = 1; i < MaxClients; i++) {
			if(IsClientInGame(i) && !IsFakeClient(i))
			{
				char steamid2[20];
				GetClientAuthId(i, AuthId_SteamID64, steamid2, sizeof(steamid2));
				if(StrEqual(steamid2, steamid64)) {
					
					//Get data for kick message
					char cAdminUsername[128], cTime[200];
					if(length > 0) SecondsToTime((length * 60), cTime); else cTime = "permanent";
					GetClientName(client, cAdminUsername, sizeof(cAdminUsername));
					ClientBanKick(i, cAdminUsername, cReason, cTime, cTime);
					
				}
			}
		}
		
		int adminID = (client == 0) ? 0 : iClientID[client];
		int serverToBan = (g_cvBansAllSrvs.IntValue == 0) ? iServerID : 0;
		char query[500];
		Format(query, sizeof(query), 
		"INSERT INTO bp_bans (pid, sid, aid, reason, length) "...
		"VALUES((SELECT id FROM bp_players WHERE steamid = '%s'), %i, %i, '%s', %i)", steamid64, serverToBan, adminID, cReason, length);
		
		
		DB.Query(OnRowInserted, query);
		
		return Plugin_Stop;
	}
	
	return Plugin_Continue;
}

public void OnBanCheck(Database db, DBResultSet results, const char[] error, any userID)
{
	if(results == null)
	{
		LogError("[BOOMPANEL] SQL ERROR: %s", error);
		return;
	}
	
	int client = GetClientOfUserId(userID);
	if(client < 1)
		return;
	
	if(results.FetchRow())
	{
		//Featch data from DB
		char cReason[100], cTime[200], cTotalTime[200], cAdminUsername[MAX_NAME_LENGTH];
		results.FetchString(0, cReason, sizeof(cReason));
		results.FetchString(2, cAdminUsername, sizeof(cAdminUsername));
		int length 	= results.FetchInt(1);
		int passed 	= results.FetchInt(3);
		
		if(passed < length || length == 0)
		{
			
			if(length > 0) SecondsToTime(((length - passed) * 60), cTime); else cTime = "permanent";
			if(length > 0) SecondsToTime((length * 60), cTotalTime); else cTotalTime = "permanent";
			ClientBanKick(client, cAdminUsername, cReason, cTotalTime, cTime);
	
		}
		
	}
	
}



public Action OnBanClient(int client, int time, int flags, const char[] reason, const char[] kick_message, const char[] command, any admin) 
{
	
	if(iServerID == -1)
	{
		ReplyToCommand(admin, "%sDatabase connection not successful, cant ban players right now!", PREFIX);
		return Plugin_Stop;
	}
	
	//Insert data in database
	char query[255], cEscReason[100];
	DB.Escape(reason, cEscReason, sizeof(cEscReason));
	int adminID = (admin == 0) ? 0 : iClientID[admin];
	int serverToBan = (g_cvBansAllSrvs.IntValue == 0) ? iServerID : 0;
	Format(query, sizeof(query), "INSERT INTO bp_bans (pid, sid, aid, reason, length) VALUES ('%i', '%i', '%i', '%s', '%i')", iClientID[client], serverToBan, adminID, reason, time);
	DB.Query(OnRowInserted, query);
	
	//Get kick message data
	char cAdminName[MAX_NAME_LENGTH], cTime[200];
	GetClientName(admin, cAdminName, sizeof(cAdminName));
	if(time > 0) SecondsToTime(time * 60, cTime); else cTime = "permanent";
	
	
	ReplyToCommand(admin, "%sPlayer successfully banned!", PREFIX);
	ClientBanKick(client, cAdminName, cEscReason, cTime, cTime);
	
	return Plugin_Stop;
}

void ClientBanKick(int client, char[] cAdminName, char[] cReason, char[] cTotalTime, char[] cTime)
{
	KickClient(client,
	"You have been banned from the server\n "...
	"\nSERVER: 		%s"...
	"\nADMIN:		%s"...
	"\nREASON:		%s"...
	"\nTOTAL:		%s"...
	"\nTIME LEFT: 	%s"...
	"\n", cServerHostName, cAdminName, cReason, cTotalTime, cTime);
}

stock void SecondsToTime(int seconds, char time[200], bool ShortDate = true)
{

	//DAYS
	int days = (seconds / (3600*24));
	if(days > 0) {
		char s_days[20]; 
		IntToString(days, s_days, sizeof(s_days));
		StrCat(time, sizeof(time), s_days);	
	}
	
	if(days == 1)
		StrCat(time, sizeof(time), " day ");	
	else if(days > 0)
		StrCat(time, sizeof(time), " days ");	
	
	if(ShortDate && days > 0)	
		return;
	
	
	//HOURS
	if(ShortDate) 
		time = "";
	int hours = (seconds / 3600) % 24;
	if(hours > 0) {
		char s_hours[20]; 
		IntToString(hours, s_hours, sizeof(s_hours));
		StrCat(time, sizeof(time), s_hours);
	}
	
	if(hours == 1)
		StrCat(time, sizeof(time), " hour ");	
	else if(hours > 0)
		StrCat(time, sizeof(time), " hours ");
		
	if(ShortDate && hours > 0)
		return;	
		
		
	//MINUTES
	if(ShortDate) 
		time = "";
	int minutes = (seconds / 60) % 60;
	if(minutes > 0) {
		char s_minutes[20]; 
		IntToString(minutes, s_minutes, sizeof(s_minutes));
		StrCat(time, sizeof(time), s_minutes);	
	}
	
	if(minutes == 1)
		StrCat(time, sizeof(time), " minute ");
	else if(minutes > 0)
		StrCat(time, sizeof(time), " minutes ");
	
	if(ShortDate && minutes > 0)
		return;	
	
}


stock bool GetCommunityID(char[] AuthID, char[] FriendID, int size) 
{ 
	if(strlen(AuthID) < 11 || AuthID[0]!='S' || AuthID[6]=='I') 
	{
		FriendID[0] = 0; 
		return false; 
	}
	
	int iUpper = 765611979; 
	int iFriendID = StringToInt(AuthID[10])*2 + 60265728 + AuthID[8]-48; 
	
	int iDiv = iFriendID/100000000; 
	int iIdx = 9-(iDiv?iDiv/10+1:0); 
	iUpper += iDiv; 
	 
	IntToString(iFriendID, FriendID[iIdx], size-iIdx); 
	iIdx = FriendID[9]; 
	IntToString(iUpper, FriendID, size); 
	FriendID[9] = iIdx; 
	
	return true; 
}  