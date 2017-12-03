void RconCommands_OnPluginStart()
{
	RegAdminCmd("sm_BPstatus", 		CMD_Status, 	ADMFLAG_RCON);
	RegAdminCmd("sm_BPbankick", 	CMD_BanKick, 	ADMFLAG_RCON);
	RegAdminCmd("sm_BPmutegagadd", 	CMD_MuteGagAdd, ADMFLAG_RCON);
	RegAdminCmd("sm_BPmutegagrem", 	CMD_MuteGagRem, ADMFLAG_RCON);
	RegAdminCmd("sm_BPmutegagres", 	CMD_MuteGagRes, ADMFLAG_RCON);
	RegAdminCmd("sm_BPreloadadmin", CMD_ReloadAdm, 	ADMFLAG_RCON);
}

public Action CMD_ReloadAdm(int client, int args)
{
	if(client != 0)
		return Plugin_Handled;
	
	char steamid[20];
	GetCmdArg(1, steamid, sizeof(steamid));
	int target = GetClientFromSteamID(steamid);
	
	if(target != -1) {
		Admins_OnClientIDRecived(target);
	}
	
	return Plugin_Handled;	
}

public Action CMD_MuteGagRes(int client, int args)
{
	if(client != 0)
		return Plugin_Handled;
	
	char steamid[20];
	GetCmdArg(1, steamid, sizeof(steamid));
	int target = GetClientFromSteamID(steamid);
	
	if(target != -1) {
		
		char cType[2], cTimeLeft[20], cLength[20];
		GetCmdArg(2, cType, sizeof(cType));
		GetCmdArg(3, cLength, sizeof(cLength));
		GetCmdArg(4, cTimeLeft, sizeof(cTimeLeft));
		int iType 		= StringToInt(cType);
		int iLength 	= StringToInt(cLength);
		int iTimeleft 	= StringToInt(cTimeLeft);
		
		RestoreMuteGag(target, iType, iLength, iTimeleft);
	}
	
	return Plugin_Handled;	
}


public Action CMD_MuteGagRem(int client, int args)
{
	if(client != 0)
		return Plugin_Handled;
	
	char steamid[20];
	GetCmdArg(1, steamid, sizeof(steamid));
	int target = GetClientFromSteamID(steamid);
	
	if(target != -1) {
		
		char cType[2];
		GetCmdArg(2, cType, sizeof(cType));
		int iType 	= StringToInt(cType);
		
		RemoveMuteGag(target, iType);
	}
	
	return Plugin_Handled;	
}


public Action CMD_MuteGagAdd(int client, int args)
{
	if(client != 0)
		return Plugin_Handled;
	
	char steamid[20];
	GetCmdArg(1, steamid, sizeof(steamid));
	int target = GetClientFromSteamID(steamid);
	
	if(target != -1) {
		
		char cType[2], cReason[150], cLength[20];
		GetCmdArg(2, cType, sizeof(cType));
		GetCmdArg(3, cReason, sizeof(cReason));
		GetCmdArg(4, cLength, sizeof(cLength));
		
		int iType 	= StringToInt(cType);
		int iLength = StringToInt(cLength);

		AddMuteGag(target, iType, iLength, iLength, cReason);

	}
	
	return Plugin_Handled;	
}

public Action CMD_BanKick(int client, int args)
{
	if(client != 0)
		return Plugin_Handled;
	
	
	char steamid[20];
	GetCmdArg(1, steamid, sizeof(steamid));
	int target = GetClientFromSteamID(steamid);
	
	if(target != -1) 
	{
		char cAdminUsername[128], cReason[150], cLength[50], cTime[200];
		GetCmdArg(2, cAdminUsername, sizeof(cAdminUsername));
		GetCmdArg(3, cReason, sizeof(cReason));
		GetCmdArg(4, cLength, sizeof(cLength));
		int iLength = StringToInt(cLength);
		if(iLength > 0) SecondsToTime(iLength * 60, cTime); else cTime = "permanent";
		ClientBanKick(target, cAdminUsername, cReason, cTime, cTime);
		ReplyToCommand(client, "[BoomPanel] Player ban kicked!");
	}
	
	return Plugin_Handled;
	
}

int GetClientFromSteamID(char steamid[20])
{
	for (int i = 1; i < MaxClients; i++) {
		if(IsClientInGame(i) && !IsFakeClient(i)) {
			char steamid2[20];
			GetClientAuthId(i, AuthId_Steam2, steamid2, sizeof(steamid2));
			if(StrEqual(steamid, steamid2))
				return i;
		}
	}
	
	return -1;
}

public Action CMD_Status(int client, int args)
{
	if(client != 0)
		return Plugin_Handled;
		
	int online = 0;
	for (int i = 1; i <= MaxClients; i++)
		if(IsClientInGame(i) && !IsFakeClient(i))
			online++;
	
	int count = 0;
		
	char currentMap[50];
	GetCurrentMap(currentMap, sizeof(currentMap));
	
	ReplyToCommand(client, "{\"stats\":{\"sid\": \"%i\", \"map\": \"%s\", \"online\": %i}, \"players\": [", iServerID, currentMap, online);
	//This is done, so everything is sorted
	AddToList(count, CS_TEAM_T);
	AddToList(count, CS_TEAM_CT);
	AddToList(count, CS_TEAM_SPECTATOR);
	AddToList(count, CS_TEAM_NONE);

	ReplyToCommand(client, "]}");
	return Plugin_Handled;
}

void AddToList(int &count, int team)
{
	for (int i = 1; i <= MaxClients; i++)
		if(IsClientInGame(i) && !IsFakeClient(i) && GetClientTeam(i) == team)
		{
			char username[MAX_NAME_LENGTH], steamid[20], cConnected[20], cIP[20], cCountry[50];
			GetClientName(i, username, sizeof(username));
			ReplaceString(username, sizeof(username), "\\", "");
			ReplaceString(username, sizeof(username), "\"", "''");
			GetClientAuthId(i, AuthId_SteamID64, steamid, sizeof(steamid));
			GetClientIP(i, cIP, sizeof(cIP));
			GeoipCountry(cIP, cCountry, sizeof(cCountry));
			int kills 	= (!IsSpectator(i)) ? GetClientFrags(i) : 0;
			int deaths 	= (!IsSpectator(i)) ? GetClientDeaths(i) : 0;
			GetClientCookie(i, hConnected, cConnected, sizeof(cConnected));
			int secondsOnline = (AreClientCookiesCached(i) && StringToInt(cConnected) > 0) ? GetTime() - StringToInt(cConnected) : 0;
			int online = (secondsOnline / 60);
			PrintToServer("%s{\"p\":%i,\"t\":%i,\"k\":%i,\"d\":%i,\"o\":%i}", (count == 0) ? "" : ",", iClientID[i], GetClientTeam(i), kills, deaths, online);
			
			count++;
		}
}