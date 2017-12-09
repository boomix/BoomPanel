void BP_OnPluginStart()
{
	BP_InitConVars();
	MuteGag_OnPluginStart();
	RconCommands_OnPluginStart();
	PlayersOnline_OnPluginStart();
	
}

public APLRes AskPluginLoad2(Handle myself, bool late, char[] error, int err_max)
{
	BP_InitConVars();
	RegPluginLibrary("boompanel");
	CreateNatives();
	return APLRes_Success;
}

void OnDBConnected()
{
	ServerID_OnDatabaseConnected();
}

void OnServerIDUpdated()
{
	for (int i = 1; i <= MaxClients; i++)
		if(IsClientInGame(i) && !IsFakeClient(i) && iClientID[i] < 1) {
			
			MuteGag_OnClientDisconnect(i);
			
			OnClientPutInServer(i);
			OnClientPostAdminCheck(i);

		}
	Call_StartForward(g_OnDatabaseReady);
	Call_Finish();
}

void OnClientIDRecived(int client)
{
	PlayersIP_OnClientIDRecived(client);
	PlayersUsername_OnClientIDRecived(client);
	Bans_OnClientIDRecived(client);
	MuteGag_OnClientIDRecived(client);
	Admins_OnClientIDRecived(client);
	PlayersOnline_OnClientIDRecived(client);
}

public void OnMapStart()
{
	Main_OnMapStart();
	ServerID_OnMapStart();
	Bans_OnMapStart();
	MuteGag_OnMapStart();
}

public void OnClientPutInServer(int client)
{
	if(!IsFakeClient(client))
	{
		MuteGag_OnClientPutInServer(client);
		Players_OnClientPutInServer(client);
	}
}

public Action Event_Disconnect(Event event, const char[] name, bool dontBroadcast) 
{
	//char reason[255]; 
	//GetEventString(event, "reason", reason, sizeof(reason)); 
	int client = GetClientOfUserId(event.GetInt("userid"));
	if(client > 0 && !IsFakeClient(client)) {
		PlayersOnline_OnClientDisconnect(client);
		Admins_OnClientDisconnect(client);
	}
	
	return Plugin_Continue; 
}

public void OnClientDisconnect(int client)
{
	MuteGag_OnClientDisconnect(client);
}

public Action Event_PlayerTeam(Handle event, const char[] name, bool dontBroadcast)
{
	int client = GetClientOfUserId(GetEventInt(event, "userid"));
	int team = GetEventInt(event, "team");
	if(!IsFakeClient(client) && team != 0) {
		MuteGag_PlayerTeam(client);
	}
}

public void OnClientPostAdminCheck(int client)
{
	if(!IsFakeClient(client))
	{
		Players_OnClientPostAdminCheck(client);
	}
}