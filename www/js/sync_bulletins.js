

window.bulletins =  {



    syncURL: "http://app.onlineportfolio.co.uk/sync/getDataBulletins.php",

    initialize: function(callback) {
		


    },
        
   
    getLastSync: function(callback) {
		
		
        window.db.transaction(
            function(tx) {
                var sql = "SELECT MAX(lastModified) as lastSync FROM bulletins";
                tx.executeSql(sql, this.txErrorHandler,
                    function(tx, results) {
                        var lastSync = results.rows.item(0).lastSync;
                        log('Last local timestamp is ' + lastSync);
                        callback(lastSync);
                    }
                );
            }
        );
    },




    sync: function(callback) {

        var self = this;
        log('Starting synchronization stuff...');
        this.getLastSync(function(lastSync){
	            self.getChanges(self.syncURL, lastSync,
                function (changes) {
                    if (changes.length > 0) {
                        self.applyChanges(changes, callback);
                    } else {
                        log('Nothing to synchronize');
       
                    }
                }
            );
        });

    },

    getChanges: function(syncURL, modifiedSince, callback) {

        $.ajax({
            url: syncURL,
            data: {modifiedSince: modifiedSince,table:'bulletins'},
            dataType:"json",
            success:function (data) {
                log("The server returned " + data.length + " changes that occurred after " + modifiedSince);
                callback(data);
            },
            error: function(model, response) {
           
            }
        });

    },

    applyChanges: function(bulletins, callback) {
        window.db.transaction(
            function(tx) {
                var l = bulletins.length;
                var sql =
                    "INSERT OR REPLACE INTO bulletins (bulletID,bulletTitle,bulletDesc,dateAdded,lastModified) " +
                    "VALUES (?, ?, ?, ?, ?)";
					
		
                log('Inserting or Updating in local database:');
				log('SQL IS '+sql);
                var e;
                for (var i = 0; i < l; i++) {
                    e = bulletins[i];    		  
                    var params = [e.bulletID, e.bulletTitle, e.bulletDesc, e.dateAdded,  e.lastModified];
                    tx.executeSql(sql, params);
                }
			//	alert('Synchronization complete (' + l + ' items synchronized)');
            },
            this.txErrorHandler,
            function(tx) {
          
            }
        );
    },

    txErrorHandler: function(tx) {
       
    }
};

bulletins.initialize(function() {
    console.log('database initialized');
});



	
function log(msg) {
   alert (msg);
}

