function socketComet(){
    socket.on('connect', function() {
        socket.on('message', function(message) {
            console.log(message);
            var data = $.parseJSON(message);
            if (data.comet === 'tothtem' || data.comet === 'module') {
                if(data.module==moduleInCourse){
                    refreshTable();
                }
            }
        });
    });
};
