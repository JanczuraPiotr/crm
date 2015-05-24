var E = {
	code : {
		OK                  : 1,
		UNKNOWN             : 0,
		NOT_LOGIN           : -1,

    EXCEPTION           : -100,
    EGENERAL            : -100,
    EARRAYINDEX         : -101,
    EFILE               : -102,
    EFILEW              : -103,
    EFILER              : -104,
    ENORECORD           : -105,
    ECONNECT            : -106,
    EBADIN              : -107,
    ENOTSET             : -108,
    EBADFORMAT          : -109,
    ECOHERENT           : -110,
    ENOENCJA            : -111,
    ENONEXISTATTRIBUTE  : -112,

		EDB                 : -200,
    EDB_GENERAL         : -200,
    EDB_CONNECT				  : -201,
    EDB_INCOMPLETELY    : -202,
    EDB_INSERT          : -203,
    EDB_UPDATE          : -204,
    EDB_UPDATE_UPDATED  : -205,
    EDB_SELECT          : -206,
    EDB_DELETE          : -207,
    EDB_FOREIGNKEY      : -208,
    EDB_NOTUNIQUE       : -209,
    EDB_OPERATION_NULL  : -210,

		AJAX_PARAM          : -10000,
    AJAX_ASYNC          : -10001,
    SERIALIZE_CREATE    : -10002,
    SERIALIZE_READ      : -10003,
    TYPEOF              : -10004
	},
	str : {
		OK                  : 'OK',
		UNKNOWN             : 'UNKNOWN',
		NOT_LOGIN           : 'NOT_LOGIN'
	},
	msg : {
		OK                  : 'Operacja wykonana poprawnie.',
		UNKNOWN             : 'Operacja nie została wykonana z niezanego powodu.',
		NOT_LOGIN           : 'Należy zalogować się.',
		EDB_FOREIGNKEY      : 'Nie udało się usunąć/zmodyfikować wskazanego rekordu z powodu zależnych od niego innych rekordów w bazie.'
	}
};