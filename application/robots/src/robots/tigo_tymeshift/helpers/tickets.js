const general_helper = require('../../../helpers/general');

function format_jobnames_to_tickets(working_tickets){
    general_helper.showDetails('[Bot Zendesk] Split jobname for ticket_id');
    return working_tickets.map(function(val){
        val.ticket_id = parseInt(val.jobname.split('-')[0].trim());
        return val;
    });
}


function notFoundMetric(){
    return {
        metric : null,
        value : null,
        stage : null,
        format_time : null,
        value_in_minutes : null
    }
}

function getTicketSlaMetric(slas, metric){

    let format_time = null;
    let value_metric = null;
    let metric_found = null;

    if(!slas || !slas.policy_metrics){
        return notFoundMetric();
    }

    metric_found = slas.policy_metrics.filter(function(val){
        return val.metric === metric;
    });

    if(metric_found.length < 1){
        return notFoundMetric();
    }
    
    metric_found = metric_found[0];
    
    format_time = getMetricFormatTime(metric_found);
    value_metric = format_time == undefined ? null : metric_found[format_time];
    
    let metric_data = {
        metric : metric_found.metric,
        stage : metric_found.stage,
        value : value_metric,
        format_time : format_time
    };

    metric_data.value_in_minutes = getMetricInMinutes(metric_data);

    return metric_data;
}


function getMetricInMinutes(metric){

    if(metric.format_time === 'days'){
        return metric.value * 1440;
    }
    if(metric.format_time === 'hours'){
        return metric.value * 60;
    }
    
    if(metric.format_time === 'minutes'){
        return metric.value;
    }

    return null;
}



function getMetricFormatTime(metric){
    var keys = Object.keys(metric);

    if(keys.includes('minutes')){
        return 'minutes';
    }
    if(keys.includes('hours')){
        return 'hours';
    }
    if(keys.includes('days')){
        return 'days';
    }

    return undefined;
}


function getsatisfactionRatingTicket(ticket){
    if(ticket.satisfaction_rating && ticket.satisfaction_rating.score !== undefined){
        return  ticket.satisfaction_rating.score
    }
    return null;
}

function getChannelTicket(ticket){
    if(ticket.via && ticket.via.channel !== undefined){
        return  ticket.via.channel
    }
    return null;
}


function format_detail_tickets(tickets, users, keymap_users){
    general_helper.showDetails('[Bot Zendesk] Format detail tickets and link identifty');
    if(tickets == undefined || tickets.constructor !== Array){
        general_helper.showDetails(`Tickets type ${typeof tickets}`);
        general_helper.showDetails(`Tickets content ${tickets}`);
        throw new Error("[BOT Zendesk] Tickets not a well formated array");
    }
    tickets = link_users_to_tickets(tickets, users, keymap_users);

    return tickets.map(function(val){

        let sla_nrt = getTicketSlaMetric(val.slas, 'next_reply_time');
        let sla_frt = getTicketSlaMetric(val.slas, 'first_reply_time');

        let date_created = get_datetime_subtract_hours(val.created_at);
        let date_updated = get_datetime_subtract_hours(val.updated_at);

        return {
            id_ticket : val.id,
            user_zendesk : val.user_zendesk,
            identify : val.identify,
            format_frt : sla_frt.format_time,
            frt : sla_frt.value,
            frt_minutes : sla_frt.value_in_minutes,
            stage_frt : sla_frt.stage,
            format_nrt : sla_nrt.format_time,
            nrt : sla_nrt.value,
            nrt_minutes : sla_nrt.value_in_minutes,
            stage_nrt : sla_nrt.stage,
            channel : getChannelTicket(val),
            status : val.status,
            type : val.type,
            priority : val.priority,
            requester_id : val.requester_id,
            submitter_id : val.submitter_id,
            assignee_id : val.assignee_id,
            satisfaction_rating : getsatisfactionRatingTicket(val),
            created_at : date_created,
            update_at : date_updated
        }

    })

}


function link_users_to_tickets(tickets, users, keymap_users){

    return tickets.map(function(val){

        val.user_zendesk = null;
        val.identify =  null;

        let found_user = users.find(function(currentUser){
            return currentUser.ticket_id === parseInt(val.id); 
        });

        if(!found_user){
            return val;
        }

        val.user_zendesk = found_user.useremail;

        if(val.user_zendesk){
            val.identify = keymap_users[val.user_zendesk];
        }
        return val;
    });
}

function get_datetime_subtract_hours(dateIni){
    dateIni = new Date(dateIni).toISOString().replace(/T/, ' ').replace(/.000Z/, ' ');

    let date = Date.parse(dateIni); 

    let five_hours = (18000 * 1000);
    date = (date - five_hours);
    date = new Date(date); 

    let _year = date.getFullYear(); 
    let _month = date.getMonth() + 1; 
    let _date = date.getDate(); 
    let _hour = date.getHours(); 
    let _minute = date.getMinutes(); 
    let _second = date.getSeconds(); 
    
    if(_month < 10){
         _month = "0"+ _month; 
    }

    if(_date < 10){
         _date = "0"+ _date; 
    } 

    if(_hour < 10){
        _hour = "0"+ _hour; 
    }

    if(_minute < 10){
        _minute = "0"+ _minute; 
    } 

    if(_second < 10){
        _second = "0"+ _second; 
    } 
    
    let str_date = _year + "-" + _month + "-" + _date + " " + _hour + ":" + _minute + ":" + _second;

    return str_date;
}


module.exports = {
    format_jobnames_to_tickets,
    format_detail_tickets,
    get_datetime_subtract_hours
}
