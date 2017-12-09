void PlayersOnline_OnPluginStart()
{
	g_ConnectionTime = new ArrayList(64);
}

void PlayersOnline_OnClientIDRecived(int client)
{
	if(GetFullConnectionTime(client) == -1 && iClientID[client] != -1) {
		StartCountingOnlineTime(client);
	}
}

void StartCountingOnlineTime(int client)
{
	char query[255];
	Format(query, sizeof(query), "INSERT IGNORE INTO bp_players_online (pid, sid, connected, disconnected) VALUES (%i, %i, now(), now())", iClientID[client], iServerID);
	DB.Query(OnRowInserted, query, _, DBPrio_Low);
	
	//Add client to arraylist
	char playerInfo[40];
	GetClientAuthId(client, AuthId_SteamID64, playerInfo, sizeof(playerInfo));
	Format(playerInfo, sizeof(playerInfo), "%s-%i", playerInfo, GetTime());
	g_ConnectionTime.PushString(playerInfo);
}

void PlayersOnline_OnClientDisconnect(int client)
{
	
	//Add in database
	if(iClientID[client] > 0 && iServerID > 0) {
		char query[500];
		Format(query, sizeof(query), "UPDATE bp_players_online SET disconnected = DATE_ADD(NOW(), INTERVAL 1 SECOND) "...
		"WHERE pid = %i AND sid = %i AND connected = disconnected ORDER BY connected DESC LIMIT 1", iClientID[client], iServerID);
		DB.Query(OnRowInserted, query, _, DBPrio_Low);
	}


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