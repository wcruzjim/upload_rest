const express = require('express');
const utilController = require('../controllers/util');
const router = express.Router();


router.get('/copydistributionjarvistosigma', utilController.copyDistributionJarvisToSigma)

module.exports = router;