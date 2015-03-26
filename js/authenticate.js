jdbc = require('jdbc');

var config = {
   libpath: '/u01/app/oracle/product/11.2.0/xe/jdbc/lib/ojdbc6.jar',
   drivername: 'oracle.jdbc.driver.OracleDriver',
   url: 'jdbc:oracle:thin:ramzi/ramzi@csevm05.crc.nd.edu:1521:XE',
 };

jdbc.initialize(config,function(err, res) {
  if(err) {
	console.log(err);
  }
});

jdbc.open(function(err,conn) {
  if (conn) {
    //Insert User Command
    jdbc.executeQuery("Select 







  }
};
