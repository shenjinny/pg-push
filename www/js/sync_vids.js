
window.vids =  {

    syncURL: "http://app.onlineportfolio.co.uk/sync/getDataVids.php",

    initialize: function(callback) {

    },
        
   
    getLastSync: function(callback) {
        window.db.transaction(
            function(tx) {
                var sql = "SELECT MAX(lastModified) as lastSync FROM theVideos";
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
            data: {modifiedSince: modifiedSince,table:'theVideos'},
            dataType:"json",
            success:function (data) {
                log("The server returned " + data.length + " changes that occurred after " + modifiedSince);
                callback(data);
            },
            error: function(model, response) {
         
            }
        });

    },

    applyChanges: function(theVideos, callback) {
        window.db.transaction(
            function(tx) {
                var l = theVideos.length;
                var sql =
                    "INSERT OR REPLACE INTO theVideos (vidID,vidTitle,dateAdded,description,vidFile,vidDuration,eventLink,ranking,views,lastModified) " +
                    "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
					
		
                log('Inserting or Updating in local database:');
				log('SQL IS '+sql);
                var e;
                for (var i = 0; i < l; i++) {
                    e = theVideos[i];    	
				  
                    var params = [e.vidID, e.vidTitle, e.dateAdded, e.description, e.vidFile, e.vidDuration, e.eventLink,  e.ranking, e.views, lastModified];
                    tx.executeSql(sql, params);
                }
                log('Synchronization complete (' + l + ' items synchronized)');
            },
            this.txErrorHandler,
            function(tx) {
                callback();
            }
        );
    },

    txErrorHandler: function(tx) {
//        alert('point 2 '+ tx.message);
    }
};

vids.initialize(function() {
    console.log('database initialized');
});


	
function log(msg) {
    $('#log').val($('#log').val() + msg + '\n');
}

