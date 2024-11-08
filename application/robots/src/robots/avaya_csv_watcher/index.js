const { exec } = require("child_process");
const config_bot = require("./config/config");
const general_helper = require('../../helpers/general');
const fs = require("fs");

// once bot start application, must be wait this time to start applications again
const wait_initial_time = 240;

// check last time unix when bot execute applications
let last_start = 0;


// stop all avaya tasks. And execute each avaya script in the list
function start_script(){

  if( Math.round( (Date.now() - last_start) / 1000) < wait_initial_time  ){
    general_helper.showDetails("Stop by recently started");
    return;
  }

  last_start = Date.now();

  general_helper.showDetails("Start killing process");
  exec("close_avaya_process.bat", function(err){

    if(err){
      general_helper.showDetails("Err trying to kill process");
    }


    exec("tasklist", function(err, stdout){
      if(err){
        general_helper.showDetails(err);
      }
      else{
        var list_process = stdout.split("\n").map(function(current_row){
          return current_row.split(" ")[0];
        });

        var list_found = list_process.filter(function(current_process){
          return (current_process.search(/^acs.*$/gi) != -1);
        });

        if(list_found.length > 50){
          general_helper.showDetails("Stop too many process");
          return;
        }
        else{

			    general_helper.showDetails("Current avaya process => " + list_found.length);

          // execute script
          config_bot.data_feed.forEach(function(current_script){

            exec(current_script.path_script, function(err){

              if(err){
                general_helper.showDetails("Error executing script " + current_script.path_script);
                general_helper.showDetails(err);
              }
              else{
                general_helper.showDetails("Running script " + current_script.path_script);
              }

            });

          });
          //  execute script

        }
      }
    });


  });

}

// check all scripts on the list
function walk_scripts(){
  const data_feed = config_bot.data_feed;
  for(var i = 0; i < data_feed.length; i++){
    analize_script(data_feed[i]);
  }

}

// // check if csv files are out of date. then execute avaya scripts
function analize_script(current_script){

  general_helper.showDetails("Check CSV " + current_script.path_csv);
  fs.stat(current_script.path_csv, function(err, stat_file){

    if(err){
      general_helper.showDetails("Err trying to read csv file", err);
      return;
    }

    let current_time = Date.now();
    let last_csv_time = Math.round((current_time - stat_file.mtimeMs) / 1000);

    if(last_csv_time > current_script.time_updated){
      general_helper.showDetails("CSV out of date " + current_script.path_csv);
      start_script();
    }
    general_helper.showDetails("File Updated " + current_script.path_csv);
  });

}



start_script();

setInterval(walk_scripts, 60000);
