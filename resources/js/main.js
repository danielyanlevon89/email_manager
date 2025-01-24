window.quitIntervals = function(limit){
    limit=limit || 1000; // could be 100 or 100000- your call
    var np, n= setInterval(function(){},100000); // not meant to ever run
    np= Math.max(0, n-limit);
    while(n> np){
        clearInterval(n--);
    }
}


window.setTemplateContent = function(value) {

    // console.log(value)
    document.getElementsByClassName('jodit-wysiwyg')[0].innerHTML = '<p>hhhhhhhhhhhhh</p>'
    document.getElementById('auto_reply').innerHTML = 'hhhhhhhhhhhhh'
    // console.log(document.getElementById("emailAccountForm").auto_reply.value)
    // document.getElementById("emailAccountForm").auto_reply.innerHTML = 'dsdfsdfsdfsdfsd';
    // console.log(document.getElementById("emailAccountForm").auto_reply.value)
}
