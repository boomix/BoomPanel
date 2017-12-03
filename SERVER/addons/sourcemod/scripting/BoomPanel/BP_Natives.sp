void CreateNatives()
{
	CreateNative("BoomPanel_GetDB", 		Native_GetDB);
	CreateNative("BoomPanel_GetServerID", 	Native_GetServerID);
	CreateNative("BoomPanel_GetClientID", 	Native_GetClientID);
	
	
	g_OnDatabaseReady = CreateGlobalForward("BoomPanel_DatabaseReady", ET_Ignore);
	
}

public int Native_GetDB(Handle plugin, int numParams)
{
	if (DB != null) {
		SetNativeCellRef(1, DB);
		return true;
	}
	return false;
}

public int Native_GetServerID(Handle plugin, int numParams)
{
	return iServerID;
}

public int Native_GetClientID(Handle plugin, int numParams)
{
	int client = GetNativeCell(1);
	return (IsClientInGame(client) && !IsFakeClient(client)) ? iClientID[client] : -1;
}
