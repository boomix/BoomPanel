void PlayersOnline2_OnPluginStart()
{
	g_ConnectionTime = new ArrayList(64);
}

void PlayersOnline2_OnClientFullDisconnect(int client)
{
	//Delete player connection time from arraylist
	char steamid[20], playerInfo[40];
	GetClientAuthId(client, AuthId_SteamID64, steamid, sizeof(steamid));
	for (int i = 0; i < g_ConnectionTime.Length; i++)
	{
		g_ConnectionTime.GetString(i, playerInfo, sizeof(playerInfo));
		if(StrContains(playerInfo, steamid) != -1)
			g_ConnectionTime.Erase(i);
	}
}

void PlayersOnline2_OnClientPutInServer(int client)
{
	iClientOnlineID[client] = -1;
	
	if(iClientID[client] > -1 && iServerID > -1) 
	{
		char query[255];
		Format(query, sizeof(query), "INSERT IGNORE INTO bp_players_online (pid, sid, connected, disconnected) VALUES (%i, %i, now(), now())", iClientID[client], iServerID);
		DB.Query(OnPlayerConnected, query, GetClientUserId(client), DBPrio_Low);
	}
	
	//Safety add that player is online
	if(iServerID > -1) 
	{
		char steamid[20];
		GetClientAuthId(client, AuthId_SteamID64, steamid, sizeof(steamid));
		char query2[500];
		Format(query2, sizeof(query2), "UPDATE bp_players SET online = %i WHERE steamid = '%s'", iServerID, steamid);
		DB.Query(OnRowInserted, query2, _, DBPrio_Low);
	}
	
	if(GetFullConnectionTime(client) == -1 && iClientID[client] != -1) {

		//Add client to arraylist
		char playerInfo[40];
		GetClientAuthId(client, AuthId_SteamID64, playerInfo, sizeof(playerInfo));
		Format(playerInfo, sizeof(playerInfo), "%s-%i", playerInfo, GetTime());
		g_ConnectionTime.PushString(playerInfo);
		
	}

}


public void OnPlayerConnected(Database db, DBResultSet results, const char[] error, any userID)
{
	if(results == null)
	{
		LogError("[BOOMPANEL] SQL ERROR (OnPlayerConnected): %s", error);
		return;
	}
	
	int client = GetClientOfUserId(userID);
	if(client < 1)
		return;
	
	if(results.InsertId != 0)
		iClientOnlineID[client] = results.InsertId;
}

void PlayersOnline2_OnClientDisconnect(int client)
{
	
	if(iClientID[client] > -1 && iClientOnlineID[client] > -1) 
	{
		char query[500];
		Format(query, sizeof(query), "UPDATE bp_players_online SET disconnected = DATE_ADD(NOW(), INTERVAL 1 SECOND) WHERE id = %i", iClientOnlineID[client]);
		DB.Query(OnRowInserted, query, _, DBPrio_Low);
	}
	
	//Safety add that player is online
	if(iServerID > -1) 
	{
		char steamid[20];
		GetClientAuthId(client, AuthId_SteamID64, steamid, sizeof(steamid));
		char query2[500];
		Format(query2, sizeof(query2), "UPDATE bp_players SET online = 0 WHERE steamid = '%s'", steamid);
		DB.Query(OnRowInserted, query2, _, DBPrio_Low);
	}
	
}

//Function
int GetFullConnectionTime(int client)
{
	char steamid[20], playerInfo[40];
	GetClientAuthId(client, AuthId_SteamID64, steamid, sizeof(steamid));
	for (int i = 0; i < g_ConnectionTime.Length; i++)
	{
		g_ConnectionTime.GetString(i, playerInfo, sizeof(playerInfo));
		if(StrContains(playerInfo, steamid) != -1) {
			char split[2][20];
			ExplodeString(playerInfo, "-", split, sizeof(split), sizeof(split[]));
			return GetTime() - StringToInt(split[1]);
		}
		
	}
	
	return -1;
}