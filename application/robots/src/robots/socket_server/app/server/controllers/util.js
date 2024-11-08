const {start_process} = require('../../../../distribution_sigma/index');

async function copyDistributionJarvisToSigma(req, res){

 let result_process = await start_process();

  res.json({
    statusCode : 200,
    statusText : 'ok',
    description : 'Copy distribution from jarvis to sigma success',
    statistics : result_process
  });
}


module.exports = {
    copyDistributionJarvisToSigma
}
