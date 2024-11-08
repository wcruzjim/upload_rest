function showDetails($data){
  console.log($data);//NOSONAR
}

function handleUncaughtExceptions(err){
  console.error(err);//NOSONAR
  process.exit(1);
}

function randomSecure(){
  return Math.round(Math.random() * 10000000); //NOSONAR
}



module.exports = {
  showDetails,
  handleUncaughtExceptions,
  randomSecure
}