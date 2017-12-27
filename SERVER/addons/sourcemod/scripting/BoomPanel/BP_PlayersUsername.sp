void PlayersUsername_OnClientIDRecived(int client)
{
	
	char ClientUsername[128], query[255], escClientUsername[128 * 2 + 1];
	GetClientName(client, ClientUsername, sizeof(ClientUsername));
	DB.Escape(ClientUsername, escClientUsername, sizeof(escClientUsername));
	
	Format(query, sizeof(query), "INSERT INTO bp_players_username (pid, username) VALUES (%i, '%s') ON DUPLICATE KEY UPDATE connections = connections + 1", iClientID[client], escClientUsername);
	DB.Query(OnRowInserted, query, _, DBPrio_Low);
	
	//Update and set the active username
	Format(query, sizeof(query), "UPDATE bp_players_username SET active = CASE username WHEN '%s' THEN 1 ELSE 0 END WHERE pid = %i", escClientUsername, iClientID[client]);
	DB.Query(OnRowInserted, query, _, DBPrio_Low);
	
}