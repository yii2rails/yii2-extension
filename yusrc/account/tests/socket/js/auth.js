
auth = {

    identity: null,

    login: function (login, password) {
        if(typeof password == 'undefined') {
            password = 'Wwwqqq111';
        }
        var formData = form.getData();
        var apiHost = hostStore.oneApiHostByUrl(formData.url);
        api.setBaseUrl(apiHost);
        var promise = new Promise(function(resolve,reject){
            var request = {
                url: "auth",
                type: "POST",
                data: {
                    login: login,
                    password: password,
                },
                success: function(data) {
                    auth.identity = data;
                    resolve(data);
                },
                error: function(data) {
                    reject(data);
                },
            };
            api.sendRequest(request);
        });
        return promise;
    },

    logout: function () {
        auth.identity = null;
    },

};
