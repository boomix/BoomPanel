public Action OnPlayerChatMessage(int client, const char[] command, int argc)
{
	//If text comes from console
	if(client < 1)
		return Plugin_Continue;
	
	//Get chat message
	char cMessage[256], cEscMessage[256];
	GetCmdArgString(cMessage, sizeof(cMessage));
	
	//Get rid of the qoutes
	strcopy(cMessage, sizeof(cMessage), cMessage[1]);
	cMessage[strlen(cMessage) - 1] = 0;
	
	//Send event to other files
	int shouldReturn = MuteGag_OnPlayerChatMessage(client, cMessage);
	if(shouldReturn > 0)
		return Plugin_Handled;
	
	//If chat log is disabled or DB/ServerID is not found
	if(g_cvChatLogEnabled.IntValue == 0 || DB == null || iServerID == -1)
		return Plugin_Continue;
	
	//If client ID is not found
	if(iClientID[client] == -1)
	{
		LogError("[BoomPanel] Failed to send message to MYSQL, because plugin couldnt get clientID");
		return Plugin_Continue;
	}
	
	DB.Escape(cMessage, cEscMessage, sizeof(cEscMessage));
		
	//Check if that is not command (later will be added support for commands also?)
	if(IsChatTrigger() || StringIsEmpty(cMessage))
		return Plugin_Continue;
		
	//Insert into database
	LogChatMessage(client, cEscMessage);
	
	return Plugin_Continue;
}

stock void LogChatMessage(int client, char[] cMessage, int type = 0)
{
	int adminID = (client == 0) ? 0 : iClientID[client];
	char query[700];
	Format(query, sizeof(query), "INSERT INTO bp_chat (pid, sid, message, type) VALUES (%i, %i, '%s', %i)", adminID, iServerID, cMessage, type);
	DB.Query(OnRowInserted, query, _, DBPrio_Low);
}


public Action OnClientCommand(int client, int args)
{
	if(IsClientInGame(client) && !IsFakeClient(client))
	{
		
		if(client < 1)
			return Plugin_Continue;
			
		//If chat log is disabled or DB/ServerID is not found
		if(g_cvChatLogEnabled.IntValue == 0 || DB == null || iServerID == -1)
			return Plugin_Continue;

		//Get written command
		char sCommand[50];
		GetCmdArg(0, sCommand, sizeof(sCommand));
		
		if(StrContains(sCommand, "sm_") != -1)
		{
			if(IsAdminCMD(sCommand))
			{
				char sFullCommand[100], sEscFullCommand[100], cEscCommand[50];
				GetCmdArgString(sFullCommand, sizeof(sFullCommand));
				DB.Escape(sCommand, cEscCommand, sizeof(cEscCommand));
				DB.Escape(sFullCommand, sEscFullCommand, sizeof(sEscFullCommand));
				Format(cEscCommand, sizeof(cEscCommand), "%s %s", cEscCommand, sEscFullCommand);
				LogChatMessage(client, cEscCommand, 1);
			}
		}
		
		
	}
	
	return Plugin_Continue;
		
		
}

bool IsAdminCMD(char[] sCommand)
{
	AdminId admin = CreateAdmin();
	//Do not include VIP commands
	admin.SetFlag(Admin_Custom1, true);
	admin.SetFlag(Admin_Custom2, true);
	admin.SetFlag(Admin_Custom3, true);
	admin.SetFlag(Admin_Custom4, true);
	admin.SetFlag(Admin_Custom5, true);
	admin.SetFlag(Admin_Custom6, true);
	admin.SetFlag(Admin_Reservation, true);
	if (CheckAccess(admin, sCommand, 0, false)) 
	{
		RemoveAdmin(admin);
		return false;
	} else {
		RemoveAdmin(admin);
		return true;
	}
}

bool StringIsEmpty(char text[256])
{
	ReplaceString(text, sizeof(text), " ", "");
	int length = strlen(text);
	if(length > 0)
		return false;
	else
		return true;
}

stock void RemoveFrontString(char[] strInput, int iSize, int iVar) {
    strcopy(strInput, iSize, strInput[iVar]);
}  