const path = require("path");

// list of csv files and avaya scripts
const data_feed = [
  {
    path_script: path.resolve(__dirname,"../../avaya_csv_watcher/operation_launchers/csr_avaya_colombia.acsauto"),
    path_csv: path.resolve(__dirname,"../../avaya_csv_watcher/result/avaya_colombia.csv"),
    time_updated : 120 // seconds to consider a file is out of date
  }
];

module.exports = {
  data_feed
}
