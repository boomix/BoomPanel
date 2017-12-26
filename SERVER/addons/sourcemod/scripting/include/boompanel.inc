#if defined _boompanel_included
 #endinput
#endif
#define _boompanel_included

/**
* Gets boompanel database connection
*
* @param Database			Where to store connection
* @return					true/false (connection successful)
*/
native bool BoomPanel_GetDB(Database &DB);

/**
* Returns current server ID from database
*
* @return int				server ID				
*/
native int BoomPanel_GetServerID();

/**
* Returns clients ID from database
*
* @return int				clients ID				
*/
native int BoomPanel_GetClientID(int client);

/**
* Called when database is loaded up
*			
*/
forward void BoomPanel_DatabaseReady();

/**
* Called when clientID is recived
*
*/
forward void BoomPanel_OnClientIDRecived(int client, int clientID);


public void __pl_boompanel__SetNTVOptional() 
{
	MarkNativeAsOptional("BoomPanel_GetDB");
	MarkNativeAsOptional("BoomPanel_GetServerID");
	MarkNativeAsOptional("BoomPanel_GetClientID");
}