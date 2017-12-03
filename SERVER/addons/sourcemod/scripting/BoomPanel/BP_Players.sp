void Players_OnClientPutInServer(int client)
{
	iClientID[client] = -1;
}

void Players_OnClientPostAdminCheck(int client)
{
	char steamid[20];
	GetClientAuthId(client, AuthId_SteamID64, steamid, sizeof(steamid));
	
	char ClientIP[20], CountrCode[3];
	GetClientIP(client, ClientIP, sizeof(ClientIP));
	GeoipCode2(ClientIP, CountrCode);
	
	char query[255];
	Format(query, sizeof(query), "INSERT IGNORE INTO bp_players (steamid, country) VALUES('%s', '%s') ON DUPLICATE KEY UPDATE country = '%s'", steamid, CountrCode, CountrCode);
	DB.Query(OnClientIsInDatabase, query, GetClientUserId(client));
	
}

public void OnClientIsInDatabase(Database db, DBResultSet results, const char[] error, any userID)
{
	if(results == null)
	{
		LogError("[BOOMPANEL] SQL ERROR (ClientIsInDatabase): %s", error);
		return;
	}
	
	if(iServerID == -1)
		return;
		
	int client = GetClientOfUserId(userID);
	if(client < 1)
		return;
		
	if(results.InsertId == 0)
	{
		char steamid[20], query[255];
		GetClientAuthId(client, AuthId_SteamID64, steamid, sizeof(steamid));
		//Check also if player is not banned in this query
		Format(query, sizeof(query), "SELECT id FROM bp_players WHERE steamid = '%s'", steamid);
		DB.Query(GetClientDBID, query, GetClientUserId(client));

	} else {
		
		iClientID[client] = results.InsertId;
		OnClientIDRecived(client);
	}

}

public void GetClientDBID(Database db, DBResultSet results, const char[] error, any userID)
{
	if(results == null)
	{
		LogError("[BOOMPANEL] SQL ERROR (ClientIsInDatabase): %s", error);
		return;
	}
	
	int client = GetClientOfUserId(userID);
	if(client < 1)
		return;
		
	iClientID[client] = results.FetchRow() ? results.FetchInt(0) : -1;
	OnClientIDRecived(client);
	
}