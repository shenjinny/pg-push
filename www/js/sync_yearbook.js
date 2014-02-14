window.yearbook =  {

	
    syncURL: "http://app.onlineportfolio.co.uk/sync/getDataYearbook.php",

    initialize: function(callback) {
		

    },
        
   
    getLastSync: function(callback) {
	
        window.db.transaction(
            function(tx) {
                var sql = "SELECT MAX(lastModified) as lastSync FROM yearbookCMS";
                tx.executeSql(sql, this.txErrorHandler,
                    function(tx, results) {
                        var lastSync = results.rows.item(0).lastSync;
                        log ('Last local timestamp is ' + lastSync);
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
            data: {modifiedSince: modifiedSince,table:'yearbookCMS'},
            dataType:"json",
            success:function (data) {
                log ("The server returned " + data.length + " changes that occurred after " + modifiedSince);
                callback(data);
            },
            error: function(model, response) {
              
            }
        });

    },

    applyChanges: function(yearbookCMS, callback) {
        window.db.transaction(
            function(tx) {
                var l = yearbookCMS.length;
                var sql =
                    "INSERT OR REPLACE INTO yearbookCMS (yearbookID, lastName,firstName,jobTitle,region,type,TA,eagle,teamName,imagePresent,ranking,lastModified, imgLink) " +
                    "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
 
                var e;
                for (var i = 0; i < l; i++) {
                    e = yearbookCMS[i];
								
					imgLink='images/noimage.jpg';
					
					var TA=e.TA;
					var eagle=e.eagle;
					
						  
                    var params = [e.yearbookID, e.lastName, e.firstName, e.jobTitle, e.region, e.type, TA, eagle, e.teamName, e.imagePresent, e.ranking, e.lastModified, imgLink];
                    tx.executeSql(sql, params);
					

					  downloadFileYearbook(e.yearbookID);		  
						
                }
			
	
            },
            this.txErrorHandler,
            function(tx) {
           
            }
        );
    },

    txErrorHandler: function(tx) {
      
    }
};




yearbook.initialize(function() {
    console.log('database initialized');
});




function log(msg) {
    $('#log').val($('#log').val() + msg + '\n');
}



  function downloadFileYearbook(yearbookID){
	  
	 
		var yearbookItem=yearbookID;


	  if (navigator.platform!='Win32') {
		  
		 
		  
							window.requestFileSystem.yearbookID=yearbookID;	
							
							 	 log ('entered the platform'+yearbookID);
								 
						window.requestFileSystem(
									 LocalFileSystem.PERSISTENT, 0, 
									 function onFileSystemSuccess(fileSystem,yearbookID) {

										  	 log ("getting  http://app.onlineportfolio.co.uk/yearbook/updates/"+yearbookItem+".jpg");	 
											 
									 fileSystem.root.getFile(
										 "dummy.html", {create: true, exclusive: false}, 
												 function gotFileEntry(fileEntry){
												 var sPath = fileEntry.fullPath.replace("dummy.html","");
												 	 
													  	 log ('got a dummy file'+sPath);	
												
												try {		 
												 var fileTransfer = new FileTransfer();
												}
												catch(err){
													log (err);
												}
												
												
												 fileEntry.remove();
												 
												   	 log ('removed');	
													 
												 fileTransfer.download(
														   "http://app.onlineportfolio.co.uk/yearbook/updates/"+yearbookItem+".jpg",
														   sPath + yearbookItem+".jpg",
														   function(theFile) {		   
															   showLinkYearbook(theFile.toURI(),yearbookItem);
														   },
														   function(error) {
					
														   }
														   );
												 }, 
												 fail);
									 }, 
									 fail);
									 
									 
									 
									 
									 	
				
									 
					  }

					  
	
 
    }
 
    function showLinkYearbook(url,yearbookItem){
		
		
				
			        window.db.transaction(function(transaction) {
				
				transaction.executeSql("UPDATE yearbookCMS SET imgLink=? WHERE yearbookID=?", [url, yearbookItem]);
				
				log ('updated DB');											

 				  });
				  


    }
 
 

 
 
 
 
 
 
 
 
 
 
    function fail(evt) {
		log ('it crapped out');
		log (evt.target.error.code);
		
     
    }