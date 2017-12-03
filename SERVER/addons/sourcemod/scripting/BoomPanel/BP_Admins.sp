void Admins_OnClientIDRecived(int client)
{
	if(g_cvAdminsEnabled.IntValue == 1 && DB != null && iServerID != -1) {
		
		//Check if admin has any group
		char query[500];
		Format(query, sizeof(query), 
		"SELECT GROUP_CONCAT(flags SEPARATOR ''), MAX(ag.immunity), MIN(IF(ag.usetime > 0, usetime - TIMESTAMPDIFF(MINUTE, add_time, now()), 1000000)) "...
		"FROM bp_admins a LEFT JOIN bp_admin_groups ag ON a.gid = ag.id "...
		"WHERE pid = %i AND (sid = %i OR sid = 0)"...
		"AND (TIMESTAMPDIFF(MINUTE, add_time, now()) < ag.usetime OR ag.usetime = 0)", iClientID[client], iServerID);
		DB.Query(GetClientAdmin, query, GetClientUserId(client));
	
	}
}

public void GetClientAdmin(Database db, DBResultSet results, const char[] error, any userID)
{
	if(results == null)
	{
		LogError("[BOOMPANEL GetClientAdmin] SQL ERROR: %s", error);
		return;
	}
	
	int client = GetClientOfUserId(userID);
	if(client < 1)
		return;
	
	if(results.FetchRow())
	{
		//Fetch SQL data
		char flags[25];
		int immunity 	= results.FetchInt(1);
		int timeleft 	= results.FetchInt(2); //After how many mins to update group again
		results.FetchString(0, flags, sizeof(flags));
		
		
		AdminId admin = CreateAdmin();
		SetAdminImmunityLevel(admin, immunity);
		for(int i = 0; i < strlen(flags); i++)
		{
			AdminFlag flag;
			if(FindFlagByChar(flags[i], flag))
				if(!admin.HasFlag(flag, Access_Effective))
					admin.SetFlag(flag, true);
		}
		SetUserAdmin(client, admin, true);
		
		//Next admin update time
		iAdminUpdateTimeleft[client] = timeleft;
		
		if(hAdminTimer[client] == null)
			hAdminTimer[client] = CreateTimer(60.0, TakeAwayMinute2, GetClientUserId(client), TIMER_REPEAT);


	}
	
}

public Action TakeAwayMinute2(Handle tmr, any userID)
{
	int client = GetClientOfUserId(userID);
	if(client > 0)
	{
		iAdminUpdateTimeleft[client] -= 1;
		if(iAdminUpdateTimeleft[client] == 0)
		{
			//Reload admin flags
			Admins_OnClientIDRecived(client);
			if(hAdminTimer[client] != null)
				hAdminTimer[client] = null;
			PrintToChat(client, "%sYour admin/vip group just got updated!", PREFIX);
		}
	}
}

void Admins_OnClientDisconnect(int client)
{
	if(hAdminTimer[client] != null)
		hAdminTimer[client] = null;
}