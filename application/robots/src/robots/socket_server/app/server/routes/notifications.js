const express = require('express');
const jwt = require('../../jwt');
const notificationsController = require('../controllers/notifications');
const router = express.Router();


router.post('/notifications', notificationsController.sendNotification)

router.post('/send_notification', jwt , notificationsController.sendNotification)

router.post('/send_notification_parent_charges', notificationsController.sendNotificationParentCharges)

module.exports = router;