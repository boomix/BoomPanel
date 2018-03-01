void PlayersIP_OnClientIDRecived(int client)
{
	
	char ClientIP[20], query[255];
	GetClientIP(client, ClientIP, sizeof(ClientIP));
	
	Format(query, sizeof(query), "INSERT INTO bp_players_ip (pid, ip) VALUES (%i, '%s') ON DUPLICATE KEY UPDATE connections = connections + 1", iClientID[client], ClientIP);
	DB.Query(OnRowInserted, query, _, DBPrio_Low);
	
	Format(query, sizeof(query), "UPDATE bp_players_ip SET active = CASE ip WHEN '%s' THEN 1 ELSE 0 END WHERE pid = %i", ClientIP, iClientID[client]);
	DB.Query(OnRowInserted, query, _, DBPrio_Low);
	
	
}