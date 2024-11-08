const presence_model = require("../models/presencia");
const { get_access_token } = require('../models/api_auth');

let list_presence = [];
let api_token = '';

function create_presence(presence_user){
    list_presence.push(presence_user.data);
}

async function save_presence(){ 
    let temp_list_presence = list_presence.slice();
    clear_presence();
    await presence_model.save_presence(temp_list_presence, api_token);
}

function clear_presence(){
    list_presence = [];
}

async function init_presence(){
    api_token =  await get_access_token();
    setInterval(save_presence, 60000);
    refresh_token();
}

function refresh_token(){
    setTimeout(async function(){
      api_token = await get_access_token();
      refresh_token()
    },3600000)  
}

init_presence();

module.exports = {
    create_presence
}