const path = require("path");

const routes = [
  {
      path :path.resolve(__dirname,"../../avaya_csv_watcher/result/avaya_directv.csv"),
      type : 1, // (1=connections,2=service_level)
      platform : 'avaya',
      interval : 4000
  }
];

module.exports = {
  routes
}
