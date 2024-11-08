const {iconList} = require('./icons');
const Model_distribution = require('../../../../../models/distribution');

/**
 * Send a native notification to an specific nsp of socket io
 * @param {object} data notification params
 * @param {object} socket socket nsp to emit notification
 * @param {boolean} test if test is true, then does not send notification and return true if validations are ok
 */
function sendNotification(data,socket, test){

    // Packet type in center socket (notification)
    let packet_type = 'a';
    let valid_icons = iconList;
    let valid_contexts = ['warning','success','danger','info','primary'];
    let valid_patterns = ['username', 'id_avaya','id_genesys','identify','operation_internal_code','pcrc'];
    let valid_positions = [1,2,3,4,5];
    let min_width = 400;
    let max_width = 900;
    let min_height = 200;
    let max_height = 500;
    let min_timer = 0; // seconds
    let max_timer = 1800;  // seconds

    let default_notification_config = {
        'icon' : 'fa-envelope',
        'context'  : 'info', 
        'movable' : true, 
        'width' : 400, 
        'height' : 200, 
        'position' : 5, 
        'resizable' : true, 
        'dark_mode' : true
    };

    // If any of required params are not set
    if(data == undefined || data.target == undefined || data.pattern == undefined){
        return 'notification.target and notification.pattern cant be empty';
    }
    // Check if pattern is in the valid pattern list
    if(valid_patterns.indexOf(data.pattern) === -1){
        return 'notification.pattern is not a valid value. Valid values [' + valid_patterns.join(',') + ']';
    }
    // Check if target is a valid type
    if(data.target.constructor !== Array && data.target.constructor !== String){
        return 'notification.target is not a valid type. Must be <String> or [<String>]';
    }
    // Check if notification_config property is defined
    if(data.notification_config == undefined || data.notification_config.constructor !== Object){
        return 'notification.notification_config must be a valid object';
    }

    // Check and set title
    if(data.notification_config.title == undefined || data.notification_config.title.length < 1 || data.notification_config.title.length > 30){
        return 'notification.notification_config.title must contain between 1 and 30 characters';
    }
    default_notification_config.title = data.notification_config.title;
    
    // Check and set text
    if(data.notification_config.text == undefined || data.notification_config.text.length < 1 || data.notification_config.text.length > 2000){
        return 'notification.notification_config.text must contain between 1 and 2000 characters';
    }
    default_notification_config.text = data.notification_config.text;
    
    
    //  ** Additional settings **

    // Check valid width
    if(data.notification_config.width !== undefined){
        if(isNaN(parseInt(data.notification_config.width))){
            return 'notification.notification_config.width is not a valid number';
        }
        
        let tmp_width = parseInt(data.notification_config.width);

        if(tmp_width < min_width || tmp_width > max_width){
            return `notification.notification_config.width must be a number between ${min_width} and ${max_width}`;
        }
        default_notification_config.width = tmp_width;
      }

    // Check valid height
    if(data.notification_config.height !== undefined){
        if(isNaN(parseInt(data.notification_config.height))){
            return 'notification.notification_config.height is not a valid number';
        }
        
        let tmp_height = parseInt(data.notification_config.height);

        if(tmp_height < min_height || tmp_height > max_height){
            return `notification.notification_config.height must be a number between ${min_height} and ${max_height}`;
        }
        default_notification_config.height = tmp_height;
        default_notification_config.height = tmp_height;
    }
    
    // Check valid resizable
    if(data.notification_config.resizable !== undefined){
        if(data.notification_config.resizable !== true && data.notification_config.resizable !== false){
            return 'notification.notification_config.resizable must be a valid boolean';
        }
        default_notification_config.resizable = data.notification_config.resizable;
    }

    // Check valid movable
    if(data.notification_config.movable !== undefined){
        if(data.notification_config.movable !== true && data.notification_config.movable !== false){
            return 'notification.notification_config.movable must be a valid boolean';
        }
        default_notification_config.movable = data.notification_config.movable;
    }

    // Check valid dark mode
    if(data.notification_config.dark_mode !== undefined){
        if(data.notification_config.dark_mode !== true && data.notification_config.dark_mode !== false){
            return 'notification.notification_config.dark_mode must be a valid boolean';
        }
        default_notification_config.dark_mode = data.notification_config.dark_mode;
    }

    // Check valid dark mode
    if(data.notification_config.allow_close !== undefined){
        if(data.notification_config.allow_close !== true && data.notification_config.allow_close !== false){
            return 'notification.notification_config.allow_close must be a valid boolean';
        }
        default_notification_config.allow_close = data.notification_config.allow_close;
    }

    // Check valid context
    if(data.notification_config.context !== undefined){
        if(valid_contexts.indexOf(data.notification_config.context) === -1){
            return 'notification.notification_config.context is not a valid context. Valid context [' + valid_contexts.join(',') + ']';
        }
        default_notification_config.context = data.notification_config.context;
    }

    // Check valid position
    if(data.notification_config.position !== undefined){
        if(valid_positions.indexOf(data.notification_config.position) === -1){
            return 'notification.notification_config.position is not a valid position. Valid positions [' + valid_positions.join(',') + ']';
        }
        default_notification_config.position = data.notification_config.position;
    }

    // Check valid icon
    if(data.notification_config.icon !== undefined){
        if(valid_icons.indexOf(data.notification_config.icon) === -1){
            return 'notification.notification_config.icon is not a valid icon. Valid icons [' + valid_icons.join(',') + ']';
        }
        default_notification_config.icon = data.notification_config.icon;
    }
    
    // Check valid timer
    if(data.notification_config.timer !== undefined){
        if(isNaN(parseInt(data.notification_config.timer))){
            return 'notification.notification_config.timer is not a valid number';
        }
        let tmp_timer = parseInt(data.notification_config.timer);
        if(tmp_timer < min_timer || tmp_timer > max_timer){
            return `notification.notification_config.timer must be a number between ${min_timer} and ${max_timer} seconds`;
        }
        
        default_notification_config.timer = tmp_timer;
    }

    // Check valid buttons
    if(data.notification_config.buttons !== undefined && data.notification_config.buttons.constructor == Array ){
        default_notification_config.buttons = data.notification_config.buttons;
    }

    // If target is an array. Attach prefix to each item
    if(data.target.constructor == Array){
    data.target = data.target.map(function(target){
        return data.pattern + "_" + target;
    });
    }
    else{
    data.target = [data.pattern + "_" +data.target];
    }

    // If param test is enable, then return all validations are ok and avoid sent notification
    if(test === true){
        return true;
    }


      // Attach multiple targets
      socket.of('/').rooms = data.target;
  
    // final prototype build to send
    let notification_prototype = {
        pattern : data.pattern,
        target : data.target,
        packet_type : packet_type,
        notification_config : default_notification_config
    } 

    // Send notification by socket
    socket.emit("message", notification_prototype);
    
    return true;
}




function checkValidParamsSendNotificationParentCharges(data){

    let valid_patterns = ["username"];
    
    // Check if pattern is in the valid pattern list
    if(data.pattern == undefined || valid_patterns.indexOf(data.pattern) === -1){
        return 'notification.pattern is not a valid value. Valid values [' + valid_patterns.join(',') + ']';
    }

    // Check if target is a valid array or string
    if(data.target.constructor !== Array && data.target.constructor !== String){
        return 'notification.target is not a valid type. Must be <String> or [<String>]';
    }
    // Check if charges is a valid array
    if(!data.charges || data.charges.constructor !== Array || data.charges.length < 1){
        return 'notification.charges is not a valid type. Must be  [<String>]';
    }

    // Test notification config
    if(sendNotification(data, null, true) !== true){
        return sendNotification(data, null, true);
    }

    return true;
}

/**
 * Get notification targets by options received
 * @param {object} data notification params
 */
async function sendNotificationParentCharges(data, socket){
    
    let validations = checkValidParamsSendNotificationParentCharges({...data});

    if(validations !== true){
        return validations;
    }
    
    let targetCharges = await Model_distribution.getParentChargesByPattern({'pattern' : data.pattern, 'target' : data.target, 'charges' : data.charges});
    
    if(targetCharges.constructor !== Array){
        return 'Error trying to search users by charge. Contact an administrator';
    }
    
    if(targetCharges.length > 0){
        data.pattern = 'identify';
        data.target = targetCharges.map(val => val.identify);
        sendNotification(data,socket);
    }
    
    return true;
}





module.exports = {
    sendNotification,
    sendNotificationParentCharges
}
