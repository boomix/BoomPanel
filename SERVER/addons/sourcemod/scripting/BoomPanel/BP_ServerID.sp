void ServerID_OnDatabaseConnected()
{
	GetServerID();
}

void ServerID_OnMapStart()
{
	if(iServerID == -1)
		GetServerID();
}

void GetServerID()
{
	//Check if we already got serverID or database connection was not successful
	if(iServerID != -1 || DB == null)
		return;
		

	char serverIP[20];	
	int port = -1;
	//Check maybe IP and PORT is written in config
	g_cvServerIP.GetString(serverIP, sizeof(serverIP));
	port = g_cvServerPORT.IntValue;
	if(StrEqual(serverIP, "") || port < 100)
	{
	
		//Get IP and PORT from server
		Handle hostIP 	= FindConVar("hostip");
		Handle hostPort = FindConVar("hostport");
		if (hostIP == INVALID_HANDLE || hostPort == INVALID_HANDLE) 
		{
			LogError("Failed to get serverIP or port, please set convar for serverIP");
			return;
		}
		int IP 	= GetConVarInt(hostIP);
		port 	= GetConVarInt(hostPort);
		Format(serverIP, sizeof(serverIP), "%d.%d.%d.%d", IP >>> 24 & 255, IP >>> 16 & 255, IP >>> 8 & 255, IP & 255);

	}

	//Get server ID
	char query[255];
	Format(query, sizeof(query), "SELECT `id` FROM bp_servers WHERE ip = '%s' AND port = %i", serverIP, port);
	DB.Query(OnGetServerID, query);
}

public void OnGetServerID(Database db, DBResultSet results, const char[] error, any data)
{
	if(results == null)
	{
		LogError("[BOOMPANEL] SQL ERROR (GetServerID): %s", error);
		return;
	}
	
	if(iServerID == -1) {
		iServerID = (results.FetchRow()) ? results.FetchInt(0) : -1;
		
		if(iServerID == -1)
			LogError("[BOOMPANEL] Failed to find server IP and PORT in database!");
		else {
			//Delete old player connections if plugin was reloaded or server crashed
			char query[255];
			Format(query, sizeof(query), "DELETE FROM bp_players_online WHERE connected = disconnected AND sid = %i", iServerID);
			DB.Query(DeleteOldConnections, query);	
		}
			
	}
}

public void DeleteOldConnections(Database db, DBResultSet results, const char[] error, any data)
{
	if(results == null)
	{
		LogError("[BOOMPANEL] SQL ERROR (DeleteOldConnections): %s", error);
		return;
	}
	
	OnServerIDUpdated();

}