
$(function() {
    var app = {
        run: function () {
            render.showCloseStatus();
            render.setLogins(login);
            render.setUrls(hostStore.all());
        },
    };
    app.run();
});
