#define PREFIX 			"\x3 \x4[BoomPanel] \x1"
#define PREFIX_BAD 		"\x3 \x7[BoomPanel] \x1"
#define TYPE_MUTE 		0
#define TYPE_GAG 		1
#define TYPE_SILENCE 	2
#define TYPE_UNMUTE 	3
#define TYPE_UNGAG 		4
#define TYPE_UNSILENCE 	5

#define MAX_REASONS 	8
char cMuteGagName[][] =  { "mute", "gag", "silence", "unmute", "ungag", "unsilence" };


Database DB;
int iServerID = -1, 
iClientID[MAXPLAYERS + 1],
iLastMuteGagTime[MAXPLAYERS + 1], 
iLastTargetID[MAXPLAYERS + 1],
iLastCommandType[MAXPLAYERS + 1],
iMuteGagTimeleft[MAXPLAYERS + 1][3],
iAdminUpdateTimeleft[MAXPLAYERS + 1];
Handle hConnected,
hMuteGagTimer[MAXPLAYERS+1],
g_OnDatabaseReady,
hAdminTimer[MAXPLAYERS + 1];
ConVar g_cvServerIP, 
g_cvServerPORT, 
g_cvBansEnabled, 
g_cvAdminsEnabled, 
g_cvMuteGagEnabled, 
g_cvChatLogEnabled;
//g_cvDefMuteGagTime;
char cServerHostName[100],
cMuteReasonsFile[PLATFORM_MAX_PATH], 
cGagReasonsFile[PLATFORM_MAX_PATH], 
cSilenceReasonsFile[PLATFORM_MAX_PATH], 
cPunishmentTimeFile[PLATFORM_MAX_PATH],
cMuteGagReason[MAXPLAYERS + 1][3][150];
char g_MuteReasons[500],
g_GagReasons[500],
g_SilenceReasons[500],
g_PunishmentTimes[500];
bool b_WaitingChatMessage[MAXPLAYERS + 1],
bShowMuteGagOnce[MAXPLAYERS + 1],
bMuteGagPermanent[MAXPLAYERS + 1][3];