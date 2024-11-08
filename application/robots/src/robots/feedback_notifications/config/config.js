require('dotenv').config({path:"../../../../.env"})

var config = {
  events : {
    // Interval of time to each worker collect data from events (ms)
    interval_get_events : 60000,
    // How early must be notified of event. (minutes)
    time_before_notification :  5,
    events_to_notify : [
      2, // Lunch
      3, // Break
      4, // meet
      5 // couching
    ]
  },
  default_notify_config : {
    "pattern" : "username",
    "target" : "identify",
    "packet_type" : "a",
    "skip_log" : true,
    "notification_config" : {
        "title" : "Titulo del mensaje",
        "text" : "Mensaje de prueba",
        "position" : 3,
        "width" : 400,
        "height" : 200,
        "resizable" : false,
        "movable" : true,
        "timer" : 30,
        "icon" : "fa-star-half-o",
        "dark_mode" : true,
        "context" : "warning"	
    }
}
}

module.exports = config;


