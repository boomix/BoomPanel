void PlayersOnline_OnClientPutInServer(int client)
{
	if(AreClientCookiesCached(client))
	{
		char cConnected[20];
		GetClientCookie(client, hConnected, cConnected, sizeof(cConnected));
		if(StringToInt(cConnected) < 1) {
			char cTime[20];
			int itime = GetTime();
			IntToString(itime, cTime, sizeof(cTime));
			SetClientCookie(client, hConnected, cTime);
		}
	}
}

void PlayersOnline_OnClientIDRecived(int client)
{
	char query[255];
	Format(query, sizeof(query), "INSERT IGNORE INTO bp_players_online (pid, sid, connected, disconnected) VALUES (%i, %i, now(), now())", iClientID[client], iServerID);
	DB.Query(OnRowInserted, query, _, DBPrio_Low);
}

void PlayersOnline_OnClientDisconnect(int client)
{
	char query[300];
	Format(query, sizeof(query), "UPDATE bp_players_online SET disconnected = NOW() WHERE pid = %i AND sid = %i AND connected = disconnected ORDER BY connected DESC LIMIT 1", iClientID[client], iServerID);
	DB.Query(OnRowInserted, query, _, DBPrio_Low);
	
	SetClientCookie(client, hConnected, "0");
}