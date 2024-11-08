const notificationsService = require('../services/notifications');


function sendNotification(req, res){

  // If user does not set notification property in the request
  if(!req.body.notification){
    return res
    .status(400)
    .json({
      statusCode : 400,
      statusText : 'Bad request',
      description : 'notification property is empty or null'        
    })
  }

  let statusSent = notificationsService.sendNotification(req.body.notification,req.io);

  // If there is any error trying to send notification
  if(statusSent !== true){
    return res
    .status(400)
    .json({
      statusCode : 400,
      statusText : 'Bad request',
      description : statusSent.constructor === String ? statusSent : 'the notification could not be sent'        
    })
  }
  // If notification sent is ok
  res.json({
    statusCode : 200,
    statusText : 'ok',
    description : 'notification was sent successfully'
  });
}

async function sendNotificationParentCharges(req, res){

  // If user does not set notification property in the request
  if(!req.body.notification){
    return res
    .status(400)
    .json({
      statusCode : 400,
      statusText : 'Bad request',
      description : 'notification property is empty or null'        
    })
  }

  let statusSent = await notificationsService.sendNotificationParentCharges(req.body.notification,req.io);
  // If there is any error trying to send notification
  if(statusSent !== true){
    return res
    .status(400)
    .json({
      statusCode : 400,
      statusText : 'Bad request',
      description : statusSent.constructor === String ? statusSent : 'the notification could not be sent'        
    })
  }
  // If notification sent is ok
  res.json({
    statusCode : 200,
    statusText : 'ok',
    description : 'notification was sent successfully'
  });
}


module.exports = {
  sendNotification,
  sendNotificationParentCharges
}
