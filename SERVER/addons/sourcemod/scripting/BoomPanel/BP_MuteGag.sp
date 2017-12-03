void MuteGag_OnPluginStart()
{
	//Get file locations
	BuildPath(Path_SM, cMuteReasonsFile, sizeof(cMuteReasonsFile), 			"configs/BoomPanel/mute-reasons.txt");
	BuildPath(Path_SM, cGagReasonsFile, sizeof(cGagReasonsFile), 			"configs/BoomPanel/gag-reasons.txt");
	BuildPath(Path_SM, cSilenceReasonsFile, sizeof(cSilenceReasonsFile), 	"configs/BoomPanel/silence-reasons.txt");
	BuildPath(Path_SM, cPunishmentTimeFile, sizeof(cPunishmentTimeFile), 	"configs/BoomPanel/punishment-times.txt");

	//Create array lists
	UpdateConfigs();

}

public Action CMD_PermaMuteGag(int client, int args)
{	
	
	if(g_cvMuteGagEnabled.IntValue == 1 && DB != null && iServerID != -1) {
		
		//Get what command player is trying to execute
		char command[50], command3[50];
		GetCmdArg(0, command, sizeof(command));
		command3 = command;
		ReplaceString(command, sizeof(command), "p", "");
		
		//Get command type
		int type = GetCommandType(command);
		iLastCommandType[client] = type;
		if(type == -1)
	        return Plugin_Handled;
		
		//Replace sm_ with !
		char command2[50];
		command2 = command3;
		ReplaceString(command2, sizeof(command2), "sm_", "!");
		
		//Check if enogh arguments are entered
		if (args < 1) {
			ReplyToCommand(client, "%s %s <player> %s", PREFIX, command2, (type <= TYPE_SILENCE) ? "[reason]" : "");
			return Plugin_Handled;
	    }
	    
	    //Get target/s
		char tname[MAX_TARGET_LENGTH], cTarget[50];
		int tlist[MAXPLAYERS], tcount; 
		bool tn_is_ml;
		GetCmdArg(1, cTarget, sizeof(cTarget));
		if ((tcount = ProcessTargetString(cTarget, client, tlist, MAXPLAYERS, COMMAND_FILTER_NO_BOTS, tname, sizeof(tname), tn_is_ml)) <= 0) {
			ReplyToCommand(client, "%sPlayer not found!", PREFIX);
			return Plugin_Handled;
		}
		
		
		//Check if there are multiple targets
		if(tcount > 1) {

			PrintToChat(client, "%sYou can only target one player with using this command!", PREFIX);
			return Plugin_Handled;
			
		}
		
		
		//Save admins last target ID
		iLastTargetID[client] = (client > 0) ? iClientID[tlist[0]] : 0;
		
		
		//If mute/gag/silence
		if(type < 3) {
			
			if(args < 2)
				ShowReasonMenu(client);
			else {
				
				//Get all arguments
				char cReason[150];
				GetCmdArg(2, cReason, sizeof(cReason));
				DBMuteGag(client, iLastTargetID[client], type, cReason, 0);
				
			}
			
		}
		
	
	}
	
	
	
	return Plugin_Handled;
}

void MuteGag_OnClientPutInServer(int client)
{
	if(iClientID[client] > 0) {
		for (int i = 0; i < 3; i++)
		{
			if((iMuteGagTimeleft[client][i] >= 0 || bMuteGagPermanent[client][i])) {
				PerformMuteGag(client, i, true);
			}
		}
	} else {
		CreateTimer(1.0, TryMuteAgainPlayer, GetClientUserId(client));
	}
}

public Action TryMuteAgainPlayer(Handle tmr, any userID)
{
	int target = GetClientOfUserId(userID);
	if(target > 0)
	{
		if(iClientID[target] > 0) {
			for (int i = 0; i < 3; i++) {
				if(iMuteGagTimeleft[target][i] >= 0 || bMuteGagPermanent[target][i]) {
					PerformMuteGag(target, i, true);
				}
			}
		}
	}
}

void MuteGag_PlayerTeam(int client)
{
	if(bShowMuteGagOnce[client]) {
		bShowMuteGagOnce[client] = false;
		
		//Go thru all mute types
		for (int i = 0; i < 3; i++)
		{
			if(iMuteGagTimeleft[client][i] >= 0 || bMuteGagPermanent[client][i]) {
				char time[200];
				SecondsToTime(iMuteGagTimeleft[client][i] * 60, time, false);
				MuteGagAlert(client, "You have an active \x6%s {BREAK} Reason: %s {BREAK} Timeleft: %s", cMuteGagName[i], cMuteGagReason[client][i], (!bMuteGagPermanent[client][i]) ? time : "permanent");
				PerformMuteGag(client, i, true);
			}
		}
		
	}
	
}

int MuteGag_OnPlayerChatMessage(int client, char message[256])
{
	if(b_WaitingChatMessage[client])
	{
		b_WaitingChatMessage[client] = false;
		if(iLastMuteGagTime[client] == -1) {
			iLastMuteGagTime[client] = ConverTimeToMinutes(message, sizeof(message));
			ShowReasonMenu(client);
			return 1;
		}
		
		else {
			DBMuteGag(client, iLastTargetID[client], iLastCommandType[client], message, iLastMuteGagTime[client]);
			return 1;
		}
	}
	
	return -1;
}

void MuteGag_OnMapStart()
{
	UpdateConfigs();
}

void UpdateConfigs()
{
	UpdateReasonArray(cMuteReasonsFile, g_MuteReasons, sizeof(g_MuteReasons));
	UpdateReasonArray(cGagReasonsFile, g_GagReasons, sizeof(g_GagReasons));
	UpdateReasonArray(cSilenceReasonsFile, g_SilenceReasons, sizeof(g_SilenceReasons));
	UpdateReasonArray(cPunishmentTimeFile, g_PunishmentTimes, sizeof(g_PunishmentTimes));
}

void UpdateReasonArray(char[] filepath, char[] string, int size)
{
	//Read from file
	File file = OpenFile(filepath, "r", false, NULL_STRING);
	file.ReadString(string, size, -1);
	file.Close();
}

int AddMenuItems(Menu menu, char[] cFileString, bool punishments = false)
{
	int count = 0;
	char cReason[MAX_REASONS][150];
	int size = ExplodeString(cFileString, "\n", cReason, sizeof(cReason), sizeof(cReason[]));
	size = (size > MAX_REASONS - 1) ? MAX_REASONS : size;
	for (int i = 0; i < size; i++) {
		if(!punishments) {
			menu.AddItem(cReason[i], cReason[i]);
			count++;
		} else {
			char cPunishTimes[2][50];
			ExplodeString(cReason[i], " - ", cPunishTimes, sizeof(cPunishTimes), sizeof(cPunishTimes[]));
			menu.AddItem(cPunishTimes[0], cPunishTimes[1]);
			count++;
		}
	}
	
	return count;
}


void MuteGag_OnClientIDRecived(int client)
{
	if(g_cvMuteGagEnabled.IntValue == 1 && DB != null && iServerID != -1) {
		
		//Set default values
		for (int i = 0; i < 3; i++) {
			iMuteGagTimeleft[client][i] = -1;
			bMuteGagPermanent[client][i] = false;
			cMuteGagReason[client][i] = "";
		}
		bShowMuteGagOnce[client] 	= true;
		hMuteGagTimer[client] 		= null;
		
		//Check if client is muted or gagged or silenced
		char query[500];
		Format(query, sizeof(query), "SELECT `mgtype`, MAX(`length` - TIMESTAMPDIFF(MINUTE, time, now())) as timeleft, MAX(`length`), MAX(`reason`) "...
		"FROM bp_mutegag mg WHERE (sid = %i OR sid = 0) AND pid = %i AND unbanned = 0 AND IF(`length` > 0, TIMESTAMPDIFF(MINUTE, time, now()), 0) <= `length` "...
		"GROUP BY mgtype", iServerID, iClientID[client]);
		DB.Query(OnMuteGagCheck, query, GetClientUserId(client), DBPrio_Low);
	}	
}

public void OnMuteGagCheck(Database db, DBResultSet results, const char[] error, any userID)
{
	if(results == null)
	{
		LogError("[BOOMPANEL] SQL ERROR: %s", error);
		return;
	}
	
	int client = GetClientOfUserId(userID);
	if(client < 1)
		return;
		
	//If results were found
	while (results.FetchRow())
	{
		int type 		= results.FetchInt(0); 
		int timeleft 	= results.FetchInt(1); 
		int length 		= results.FetchInt(2); 
		char reason[150];
		results.FetchString(3, reason, sizeof(reason));
		cMuteGagReason[client][type] = reason;

		AddMuteGag(client, type, timeleft, length);
	}
	
}

void AddMuteGag(int client, int type, int timeleft, int length, char[] cReason = "")
{
	if(length == 0 || length > 0 && timeleft > 0) {
		
		if(!StrEqual(cReason, ""))
			PerformMuteGag(client, type, true);
			
		if(iMuteGagTimeleft[client][type] < length || length == 0) {
			
			iMuteGagTimeleft[client][type] = timeleft;
			bMuteGagPermanent[client][type] = (length == 0) ? true : false;
			
			if(hMuteGagTimer[client] == null && !bMuteGagPermanent[client][type])
				hMuteGagTimer[client] = CreateTimer(60.0, TakeAwayMinute, GetClientUserId(client), TIMER_REPEAT);
			
			if(!StrEqual(cReason, ""))
			{
				char cTime[200];
				SecondsToTime(length * 60, cTime, false);
				MuteGagAlert(client, "You just received an \x6%s {BREAK} Reason: %s {BREAK} Length: %s", cMuteGagName[type], cReason, cTime);
			}
			
		}
	}
}

void RemoveMuteGag(int client, int type)
{
	iMuteGagTimeleft[client][type] = -1;
	bMuteGagPermanent[client][type] = false;
	PerformMuteGag(client, type, false);
	MuteGagAlert(client, "You just received a \x6%s", cMuteGagName[GetOpositeType(type)]);
}

void RestoreMuteGag(int client, int type, int length, int timeleft)
{
	iMuteGagTimeleft[client][type] = timeleft;
	bMuteGagPermanent[client][type] = (length == 0) ? true : false;
	PerformMuteGag(client, type, true);
	char cTime[200];
	SecondsToTime(timeleft * 60, cTime, false);
	MuteGagAlert(client, "Your \x6%s\x1 was restored! {BREAK} Timeleft: %s", cMuteGagName[type], cTime);
}

void MuteGag_OnClientDisconnect(int client)
{
	if(hMuteGagTimer[client] != null)
		hMuteGagTimer[client] = null;
}

public Action TakeAwayMinute(Handle tmr, any userID)
{
	int client = GetClientOfUserId(userID);
	if(client > 0)
	{
		//Go thru all mute types
		for (int i = 0; i < 3; i++) {
			if(iMuteGagTimeleft[client][i] > -1 && !bMuteGagPermanent[client][i])
				if(iMuteGagTimeleft[client][i] > 0)
					iMuteGagTimeleft[client][i] -= 1;
				else
					RemoveMuteGag(client, i);
		}
	}
}



//-- ALL COMMANDS (mute, gag, ungag, unmUte, silence, unsilence)--//
public Action OnPlayerMuteGag(int client, const char[] command, int args)
{
	if(g_cvMuteGagEnabled.IntValue == 1 && DB != null && iServerID != -1) {
		
		/* Few things might be taken from sourcecomms */ 
		
		//Check if client has permissions
		if (client && !CheckCommandAccess(client, command, ADMFLAG_CHAT))
			return Plugin_Stop;
		
		//Manually send this command to bp_chat, so it gets logged (because such command already exists in sourcemod by default)
		char cMessage[256], cEscMessage[256];
		GetCmdArgString(cMessage, sizeof(cMessage));
		DB.Escape(cMessage, cEscMessage, sizeof(cEscMessage));
		Format(cEscMessage, sizeof(cEscMessage), "%s %s", command, cEscMessage);
		LogChatMessage(client, cEscMessage, 1);
		
		
		//Get command type
		int type = GetCommandType(command);
		iLastCommandType[client] = type;
		if(type == -1)
	        return Plugin_Stop;
		
		//Replace sm_ with !
		char command2[50];
		Format(command2, sizeof(command2), "%s", command);
		ReplaceString(command2, sizeof(command2), "sm_", "!");
		
		//Check if enogh arguments are entered
		if (args < 1) {
			ReplyToCommand(client, "%s %s <player> %s", PREFIX, command2, (type <= TYPE_SILENCE) ? "[time] [reason]" : "");
			return Plugin_Stop;
	    }
	    
	    //Get target/s
		char tname[MAX_TARGET_LENGTH], cTarget[50];
		int tlist[MAXPLAYERS], tcount; 
		bool tn_is_ml;
		GetCmdArg(1, cTarget, sizeof(cTarget));
		if ((tcount = ProcessTargetString(cTarget, client, tlist, MAXPLAYERS, COMMAND_FILTER_NO_BOTS, tname, sizeof(tname), tn_is_ml)) <= 0) {
			ReplyToCommand(client, "%sPlayer not found!", PREFIX);
			return Plugin_Stop;
		}
		
		
		//Give just casual mute/gag if there are multiple targets
		if(tcount > 1) {

			//Loop thru all targets
			for (int i = 0; i < tcount; i++) {
				if(IsClientInGame(tlist[i]) && !IsFakeClient(tlist[i])) {
					
					bool b = (type < 3) ? true : false;
					MuteGagAlert(tlist[i], "You just received a%s\x6%s", (b) ? " temporary " : " ", cMuteGagName[type]);
					PerformMuteGag(tlist[i], type, b);
	
				}
			}
			
			return Plugin_Stop;
			
		}
		
		
		//Save admins last target ID
		iLastTargetID[client] = (client > 0) ? iClientID[tlist[0]] : 0;
		
		
		//If mute/gag/silence
		if(type < 3) {
			
			if(args < 2)
				ShowTimeMenu(client);
			else if(args < 3)
				ShowReasonMenu(client);
			else {
				
				//Get all arguments
				char cReason[150], cTime[10];
				GetCmdArg(2, cTime, sizeof(cTime));
				GetCmdArg(3, cReason, sizeof(cReason));
				int iBanTime = ConverTimeToMinutes(cTime, sizeof(cTime));
				DBMuteGag(client, iLastTargetID[client], type, cReason, iBanTime);
				
			}
			
		//If unmute/ungag/unsilence	
		} else {
			DBMuteGag(client, iLastTargetID[client], type);
		}
		
	
	}
	
	return Plugin_Stop;
}

void DBMuteGag(int admin, int clientID, int type, char[] reason = "", int time = -1)
{
	//Update last target for admin
	int adminID = (admin == 0) ? 0 : iClientID[admin];
	
	//SQL stuff
	char query[500];
	if(type < 3) {
		Format(query, sizeof(query), 
		"INSERT INTO bp_mutegag (pid, sid, aid, mgtype, length, reason) "...
		"VALUES(%i, %i, %i, %i, %i, '%s')", 
		clientID, iServerID, adminID, type, time, reason);
	} else {
		Format(query, sizeof(query), 
		"UPDATE bp_mutegag SET unbanned = 1 WHERE pid = %i AND sid = %i AND mgtype = %i", 
		clientID, iServerID, GetOpositeType(type));
	}
	DB.Query(OnRowInserted, query, _, DBPrio_Low);
	
	
	
	//Perform mute/gag on player if hes still ingame
	int target = -1;
	for (int i = 1; i < MaxClients; i++) {
		if(IsClientInGame(i) && !IsFakeClient(i) && iClientID[i] == clientID) {
			target = i;
			
			if(type < 3) {
				char cTime[200];
				SecondsToTime(time * 60, cTime, false);
				bool b = (type < 3) ? true : false;
				PerformMuteGag(i, type, b);
				if(time == 0)
					cTime = "Permanent";
					
				MuteGagAlert(i, "You have an active \x6%s {BREAK} Reason: %s {BREAK} Length: %s", cMuteGagName[type], reason, cTime);
				
				if(hMuteGagTimer[i] == null)
					hMuteGagTimer[i] = CreateTimer(60.0, TakeAwayMinute, GetClientUserId(i), TIMER_REPEAT);
					
				iMuteGagTimeleft[i][type] = time;
				
				if(time == 0)
					bMuteGagPermanent[i][type] = true;
				
			} else {
				RemoveMuteGag(target, GetOpositeType(type));
			}
			
				
			//Print to admin that everything was successful
			if(target > -1) {
				PrintToChat(admin, "%s%s successfully set for \x6%N!", PREFIX, cMuteGagName[type], target);
				
				//Print to everyone that player recived mute/gag
				MuteGagAlertAll((type < 3) ? false : true, "Admin %N just set {BREAK} an \x6%s\x1 for \x04%N", admin, cMuteGagName[type], target);
			}
			
		}	
	}


}

void ShowTimeMenu(int admin)
{
	iLastMuteGagTime[admin] = -1;
	Menu menu = new Menu(MenuHandler_OnAdminSelectsTime);
	menu.SetTitle("Select time");
	AddMenuItems(menu, g_PunishmentTimes, true);
	menu.AddItem("-1", "Custom");
	menu.ExitButton = true;
	menu.Display(admin, 0);
}

public int MenuHandler_OnAdminSelectsTime(Menu menu, MenuAction action, int client, int param)
{
	if (action == MenuAction_Select)
	{
		char cLength[5];
		menu.GetItem(param, cLength, sizeof(cLength));
		int iLength = StringToInt(cLength);
		if(iLength >= 0) {
			iLastMuteGagTime[client] = iLength;
			ShowReasonMenu(client);
		} else if(iLength == -1) {
			ReplyToCommand(client, "%sPlease enter custom time in chat, expl: (1d;30min;2h)", PREFIX);
			b_WaitingChatMessage[client] = true;
		}
	}

	else if (action == MenuAction_End)
		delete menu;
}



void ShowReasonMenu(int admin)
{
	Menu menu = new Menu(MenuHandler_OnAdminSelectsReason);
	menu.SetTitle("Select reason");
	
	if(iLastCommandType[admin] == TYPE_MUTE)
		AddMenuItems(menu, g_MuteReasons);
	else if(iLastCommandType[admin] == TYPE_GAG)
		AddMenuItems(menu, g_GagReasons);
	else if(iLastCommandType[admin] == TYPE_SILENCE)	
		AddMenuItems(menu, g_SilenceReasons);
		
	menu.AddItem("customreason", "Custom");	
	menu.ExitButton = true;
	
	menu.Display(admin, 0);
}

public int MenuHandler_OnAdminSelectsReason(Menu menu, MenuAction action, int client, int param)
{
	if (action == MenuAction_Select)
	{
		char cReason[150];
		menu.GetItem(param, cReason, sizeof(cReason));
		if(!StrEqual(cReason, "customreason")) {
			
			//Update just the info about mute/gag
			if(iLastTargetID[client] > 0)
				DBMuteGag(client, iLastTargetID[client], iLastCommandType[client], cReason, iLastMuteGagTime[client]);
			else
				PrintToChat(client, "%sSorry, we got some kind of a problem!", PREFIX);
		
		} else {
			
			PrintToChat(client, "%sPlease enter custom reason in chat:", PREFIX);
			b_WaitingChatMessage[client] = true;
			
		}
	
	}

	else if (action == MenuAction_End)
		delete menu;
}

void MuteGagAlert(int client, char[] message, any:...) {
	char line[33] = "--------------------------------", buffer[254], brakeMessage[4][254];
	VFormat(buffer, sizeof(buffer), message, 3);
	char breakWord[10];
	Format(breakWord, sizeof(breakWord), "%s", (StrContains(buffer, " {BREAK} ") != -1) ? " {BREAK} " : "{BREAK}" );
	int len = ExplodeString(buffer, breakWord, brakeMessage, sizeof(brakeMessage), sizeof(brakeMessage[]));
	
	PrintToChat(client, "%s%s", PREFIX_BAD, line);
	for (int i = 0; i < len; i++)
		PrintToChat(client, "%s%s", PREFIX_BAD, brakeMessage[i]);
	PrintToChat(client, "%s%s", PREFIX_BAD, line);
}

void MuteGagAlertAll(bool positive, char[] message, any:...) {
	char line[33] = "--------------------------------", buffer[254], brakeMessage[4][254], cPrefix[50];
	VFormat(buffer, sizeof(buffer), message, 3);
	char breakWord[10];
	Format(breakWord, sizeof(breakWord), "%s", (StrContains(buffer, " {BREAK} ") != -1) ? " {BREAK} " : "{BREAK}" );
	int len = ExplodeString(buffer, breakWord, brakeMessage, sizeof(brakeMessage), sizeof(brakeMessage[]));
	Format(cPrefix, sizeof(cPrefix), "%s", (!positive) ? PREFIX_BAD : PREFIX);
	
	PrintToChatAll("%s%s", cPrefix, line);
	for (int i = 0; i < len; i++)
		PrintToChatAll("%s%s", cPrefix, brakeMessage[i]);
	PrintToChatAll("%s%s", cPrefix, line);
}


stock int ConverTimeToMinutes(char[] time, int size)
{
	//If just casual date is entered
	if(StrContains(time, "d") == -1 && StrContains(time, "h") == -1 && StrContains(time, "m") == -1)
		return StringToInt(time);
	
	int idays, ihours, iminutes;
	char replacement[10], days[10], hours[10], minutes[10];
	if(StrContains(time, "d") != -1) {
		SplitString(time, "d", days, sizeof(days));
		idays = StringToInt(days) * 86400;
		Format(replacement, sizeof(replacement), "%id", StringToInt(days));
		ReplaceString(time, size, replacement, "", true);
	}
	if(StrContains(time, "h") != -1) {
		SplitString(time, "h", hours, sizeof(hours));
		ihours = StringToInt(hours) * 3600;
		Format(replacement, sizeof(replacement), "%id", StringToInt(hours));
		ReplaceString(time, size, replacement, "", true);
		
	}
	if(StrContains(time, "m") != -1) {
		SplitString(time, "m", minutes, sizeof(minutes));
		iminutes = (StringToInt(minutes)) * 60;
	}
	return RoundToFloor((idays + ihours + iminutes) / 60.0);
	
}

void PerformMuteGag(int client, int type, bool b)
{
	
	if(type == TYPE_GAG || type == TYPE_UNGAG)
		BaseComm_SetClientGag(client, b);
	else if(type == TYPE_MUTE || type == TYPE_UNMUTE)
		BaseComm_SetClientMute(client, b);
	else if(type == TYPE_SILENCE || type == TYPE_UNSILENCE) {
		BaseComm_SetClientMute(client, b);
		BaseComm_SetClientGag(client, b);
	}
}

int GetCommandType(const char[] command)
{
	if (StrEqual(command, "sm_gag", false))
		return TYPE_GAG;
	else if (StrEqual(command, "sm_mute", false))
		return TYPE_MUTE;
	else if (StrEqual(command, "sm_ungag", false))
		return TYPE_UNGAG;
	else if (StrEqual(command, "sm_unmute", false))
		return TYPE_UNMUTE;
	else if (StrEqual(command, "sm_silence", false))
		return TYPE_SILENCE;
	else if (StrEqual(command, "sm_unsilence", false))
		return TYPE_UNSILENCE;
	else
		return -1;
}

int GetOpositeType(int type)
{
	if(type == TYPE_MUTE)
		return TYPE_UNMUTE;
	else if(type == TYPE_GAG)
		return TYPE_UNGAG;
	else if(type == TYPE_SILENCE)
		return TYPE_UNSILENCE;
	else if(type == TYPE_UNMUTE)
		return TYPE_MUTE;
	else if(type == TYPE_UNGAG)
		return TYPE_GAG;
	else if(type == TYPE_UNSILENCE)
		return TYPE_SILENCE;
	else
		return type;
}